<?php
class qa_html_theme_layer extends qa_html_theme_base
{
    public function head_css()
    {
        parent::head_css();

        if ($this->template === 'question' && qa_opt('roulette_ans_enabled')) {
            $this->output(<<<'HTML'
            <style>
                .qa-vor-wrapper {
                    margin: 15px 0;
                    padding: 18px;
                    background: #181a20;
                    border: 1px solid #2b3139;
                    border-radius: 12px;
                    text-align: center;
                    color: #eaecef;
                    max-width: 420px;
                }
                .qa-vor-title {
                    font-size: 16px;
                    font-weight: 700;
                    margin-bottom: 12px;
                    color: #fcd535;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }
                .qa-vor-wheel-container {
                    position: relative;
                    width: 260px;
                    height: 260px;
                    margin: 0 auto 15px auto;
                }
                .qa-vor-pointer {
                    position: absolute;
                    top: -10px;
                    left: 50%;
                    transform: translateX(-50%);
                    width: 0;
                    height: 0;
                    border-left: 14px solid transparent;
                    border-right: 14px solid transparent;
                    border-top: 24px solid #fcd535;
                    z-index: 10;
                    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.6));
                }
                #qa-vor-canvas {
                    width: 260px;
                    height: 260px;
                    border-radius: 50%;
                    border: 5px solid #2b3139;
                    box-shadow: 0 0 18px rgba(0,0,0,0.6);
                    transition: transform 4s cubic-bezier(0.17, 0.67, 0.12, 0.99);
                }
                .qa-vor-btn {
                    background: linear-gradient(135deg, #fcd535 0%, #f0b90b 100%);
                    color: #181a20;
                    border: none;
                    padding: 10px 22px;
                    font-size: 14px;
                    font-weight: 800;
                    border-radius: 8px;
                    cursor: pointer;
                    text-transform: uppercase;
                    transition: opacity 0.2s, transform 0.1s;
                }
                .qa-vor-btn:hover { opacity: 0.92; }
                .qa-vor-btn:active { transform: scale(0.97); }
                .qa-vor-btn:disabled {
                    background: #474d57;
                    color: #848e9c;
                    cursor: not-allowed;
                }
                .qa-vor-result-msg {
                    margin-top: 12px;
                    font-size: 14px;
                    font-weight: 600;
                    min-height: 20px;
                }
            </style>
HTML
            );
        }
    }

    public function form_fields($form, $columns)
    {
        parent::form_fields($form, $columns);

        // Inject right into the answer form (a_form)
        if ($this->template === 'question' && qa_opt('roulette_ans_enabled') && isset($form['tags']) && strpos($form['tags'], 'name="a_form"') !== false) {
            $colorTruth = qa_html(qa_opt('roulette_ans_color_truth') ?: '#00b894');
            $colorDare  = qa_html(qa_opt('roulette_ans_color_dare') ?: '#d63031');

            // Dynamically resolve current plugin directory URL
            $pluginFolder = basename(dirname(__FILE__));
            $badgeBaseUrl = qa_html(qa_opt('site_url') . 'qa-plugin/' . $pluginFolder . '/badge.php');

            $this->output('<div class="qa-vor-wrapper" id="qa-vor-box" data-truth="' . $colorTruth . '" data-dare="' . $colorDare . '" data-badge-url="' . $badgeBaseUrl . '">');
            $this->output(<<<'HTML'
                <div class="qa-vor-title">🎲 Roulette: Truth or Dare?</div>
                <div class="qa-vor-wheel-container">
                    <div class="qa-vor-pointer"></div>
                    <canvas id="qa-vor-canvas" width="260" height="260"></canvas>
                </div>
                <button type="button" class="qa-vor-btn" id="qa-vor-spin-btn">Spin Truth or Dare</button>
                <div class="qa-vor-result-msg" id="qa-vor-status">Spin before answering to see what you get</div>
            </div>

            <script>
            (function() {
                const box = document.getElementById("qa-vor-box");
                const canvas = document.getElementById("qa-vor-canvas");
                if (!box || !canvas) return;
                const ctx = canvas.getContext("2d");
                const spinBtn = document.getElementById("qa-vor-spin-btn");
                const statusEl = document.getElementById("qa-vor-status");

                const colorTruth   = box.getAttribute("data-truth") || "#00b894";
                const colorDare    = box.getAttribute("data-dare") || "#d63031";
                const badgeBaseUrl = box.getAttribute("data-badge-url");

                const segments = [
                    { label: "TRUTH", color: colorTruth },
                    { label: "DARE",  color: colorDare },
                    { label: "TRUTH", color: colorTruth },
                    { label: "DARE",  color: colorDare },
                    { label: "TRUTH", color: colorTruth },
                    { label: "DARE",  color: colorDare }
                ];

                const numSegments = segments.length;
                const arcSize = (2 * Math.PI) / numSegments;
                let currentRotation = 0;
                let hasSpun = false;

                function drawWheel() {
                    const centerX = canvas.width / 2;
                    const centerY = canvas.height / 2;
                    const radius = centerX;

                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    for (let i = 0; i < numSegments; i++) {
                        const angle = i * arcSize;
                        ctx.beginPath();
                        ctx.moveTo(centerX, centerY);
                        ctx.arc(centerX, centerY, radius, angle, angle + arcSize);
                        ctx.closePath();
                        ctx.fillStyle = segments[i].color;
                        ctx.fill();
                        ctx.lineWidth = 2;
                        ctx.strokeStyle = "#181a20";
                        ctx.stroke();

                        ctx.save();
                        ctx.translate(centerX, centerY);
                        ctx.rotate(angle + arcSize / 2);
                        ctx.textAlign = "right";
                        ctx.fillStyle = "#ffffff";
                        ctx.font = "bold 15px Arial, sans-serif";
                        ctx.shadowColor = "rgba(0,0,0,0.7)";
                        ctx.shadowBlur = 4;
                        ctx.fillText(segments[i].label, radius - 18, 5);
                        ctx.restore();
                    }
                }

                drawWheel();

                function buildBadgeUrl(resultType, bgColor) {
                    const cleanHex = bgColor.replace("#", "");
                    return `${badgeBaseUrl}?type=${encodeURIComponent(resultType)}&color=${encodeURIComponent(cleanHex)}`;
                }

                function insertBadgeIntoAnswer(resultType, bgColor) {
                    const imgUrl = buildBadgeUrl(resultType, bgColor);
                    const badgeHtml = `<p><img src="${imgUrl}" alt="Roulette: ${resultType}" width="340" height="64" /></p><p><br></p>`;
                    const badgeText = `[🎲 ROULETTE: I LANDED ON ${resultType} — The question author can leave me a ${resultType.toLowerCase()} in the comments]\n\n`;

                    if (window.CKEDITOR && CKEDITOR.instances) {
                        for (let instanceName in CKEDITOR.instances) {
                            if (instanceName.indexOf("content") !== -1 || instanceName.indexOf("a_") !== -1) {
                                const editor = CKEDITOR.instances[instanceName];
                                editor.insertHtml(badgeHtml);
                                return;
                            }
                        }
                    }

                    const textarea = document.querySelector('form[name="a_form"] textarea[name="a_content"], textarea[name="content"]');
                    if (textarea) {
                        textarea.value = badgeText + textarea.value;
                    }
                }

                spinBtn.addEventListener("click", function() {
                    if (hasSpun) return;
                    hasSpun = true;
                    spinBtn.disabled = true;
                    statusEl.textContent = "Spinning...";

                    const extraSpins = 5 + Math.floor(Math.random() * 4);
                    const randomDegree = Math.floor(Math.random() * 360);
                    currentRotation += (extraSpins * 360) + randomDegree;

                    canvas.style.transform = `rotate(${currentRotation}deg)`;

                    setTimeout(function() {
                        const normalizedAngle = (360 - (currentRotation % 360) + 270) % 360;
                        const segmentAngle = 360 / numSegments;
                        const winningIndex = Math.floor(normalizedAngle / segmentAngle);
                        const winner = segments[winningIndex];

                        statusEl.innerHTML = `You landed on <span style="color:${winner.color}; font-weight:800;">${winner.label}</span>! Badge inserted into your answer.`;
                        insertBadgeIntoAnswer(winner.label, winner.color);
                    }, 4100);
                });
            })();
            </script>
HTML
            );
        }
    }
}
