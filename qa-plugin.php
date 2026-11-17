<?php
if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit;
}

qa_register_plugin_module(
    'module',
    'qa-roulette-answer-admin.php',
    'qa_roulette_answer_admin',
    'Truth or Dare Roulette Admin'
);

qa_register_plugin_layer(
    'qa-roulette-answer-layer.php',
    'Truth or Dare Roulette Layer'
);
