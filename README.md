
# Q2A Truth or Dare Answer Roulette (`q2a-truth-or-dare-answer`)

A lightweight, customizable casino-style **Truth or Dare** roulette wheel plugin for **Question2Answer (Q2A)** that adds an interactive pre-answer game right above the answer editor.

Before submitting an answer, users can spin the two-tone roulette wheel to land on either **TRUTH** or **DARE**. Once the wheel stops, a real dynamic PNG badge is automatically inserted into their answer editor (compatible with both **CKEditor** and standard plain textareas) so the question author knows whether to leave a Truth or a Dare in the comments.

## Features

- **Two-Tone Customizable Roulette Wheel:** HTML5 Canvas wheel with smooth deceleration and golden top pointer[cite: 27].
- **Admin Color Pickers & HEX Sync:** Choose any background colors for the **TRUTH** and **DARE** segments directly from the Q2A Admin panel (`Admin -> Plugins -> Options`)[cite: 27].
- **Real PNG Badge Generation (`badge.php`):** Generates dynamic PNG badges on the fly using PHP GD, preventing CKEditor Advanced Content Filter (ACF) or Q2A HTML sanitizer issues[cite: 27].
- **Plug & Play:** Includes standard `metadata.json` for easy enabling/disabling from the Q2A administration panel[cite: 27].

## Requirements

- Question2Answer **1.7+**[cite: 27]
- PHP **7.4+** with the **GD extension** enabled (`ext-gd`)[cite: 27]

## Installation

1. Download the latest release or clone this repository into your Q2A `qa-plugin/` directory[cite: 27]:
   ```bash
   git clone https://github.com/Monkey80s/q2a-truth-or-dare-answer.git
