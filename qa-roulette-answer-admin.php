<?php
class qa_roulette_answer_admin
{
    public function allow_template($template)
    {
        return ($template !== 'admin');
    }

    public function option_default($option)
    {
        switch ($option) {
            case 'roulette_ans_enabled':
                return 1;
            case 'roulette_ans_color_truth':
                return '#00b894';
            case 'roulette_ans_color_dare':
                return '#d63031';
            default:
                return null;
        }
    }

    public function admin_form(&$qa_content)
    {
        $saved = false;

        if (qa_clicked('roulette_ans_save')) {
            qa_opt('roulette_ans_enabled', (int)qa_post_text('roulette_ans_enabled'));

            $cTruth = trim((string)qa_post_text('roulette_ans_color_truth'));
            $cDare  = trim((string)qa_post_text('roulette_ans_color_dare'));

            if (!preg_match('/^#[a-fA-F0-9]{6}$/', $cTruth)) {
                $cTruth = '#00b894';
            }
            if (!preg_match('/^#[a-fA-F0-9]{6}$/', $cDare)) {
                $cDare = '#d63031';
            }

            qa_opt('roulette_ans_color_truth', $cTruth);
            qa_opt('roulette_ans_color_dare', $cDare);
            $saved = true;
        }

        $valTruth = qa_html(qa_opt('roulette_ans_color_truth') ?: '#00b894');
        $valDare  = qa_html(qa_opt('roulette_ans_color_dare') ?: '#d63031');

        $htmlTruth = sprintf(
            '<div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">' .
            '<input type="color" id="picker_truth" value="%1$s" onchange="document.getElementById(\'hex_truth\').value=this.value;" style="width:55px; height:38px; border:1px solid #ccc; border-radius:6px; cursor:pointer; padding:2px;">' .
            '<input type="text" id="hex_truth" name="roulette_ans_color_truth" value="%1$s" maxlength="7" oninput="if(/^#[0-9A-Fa-f]{6}$/.test(this.value)) document.getElementById(\'picker_truth\').value=this.value;" style="width:95px; padding:6px 8px; border-radius:6px; border:1px solid #ccc; font-family:monospace;">' .
            '</div>',
            $valTruth
        );

        $htmlDare = sprintf(
            '<div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">' .
            '<input type="color" id="picker_dare" value="%1$s" onchange="document.getElementById(\'hex_dare\').value=this.value;" style="width:55px; height:38px; border:1px solid #ccc; border-radius:6px; cursor:pointer; padding:2px;">' .
            '<input type="text" id="hex_dare" name="roulette_ans_color_dare" value="%1$s" maxlength="7" oninput="if(/^#[0-9A-Fa-f]{6}$/.test(this.value)) document.getElementById(\'picker_dare\').value=this.value;" style="width:95px; padding:6px 8px; border-radius:6px; border:1px solid #ccc; font-family:monospace;">' .
            '</div>',
            $valDare
        );

        return array(
            'ok' => $saved ? 'Truth or Dare Roulette settings saved successfully.' : null,
            'fields' => array(
                array(
                    'label' => 'Enable Truth or Dare Roulette on answers',
                    'tags' => 'name="roulette_ans_enabled"',
                    'value' => qa_opt('roulette_ans_enabled'),
                    'type' => 'checkbox',
                ),
                array(
                    'label' => 'TRUTH segment background color',
                    'type' => 'custom',
                    'html' => $htmlTruth,
                ),
                array(
                    'label' => 'DARE segment background color',
                    'type' => 'custom',
                    'html' => $htmlDare,
                ),
            ),
            'buttons' => array(
                array(
                    'label' => 'Save Changes',
                    'tags' => 'name="roulette_ans_save"',
                ),
            ),
        );
    }
}
