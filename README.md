# 🔐 Pascal Schärli's Cryptography Playground

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![Made with: HTML/CSS/JS](https://img.shields.io/badge/Made%20with-HTML%2FCSS%2FJS-orange)](https://developer.mozilla.org/en-US/docs/Web/HTML)

Welcome to the source code of [pascscha.ch](https://pascscha.ch) – my place to write about my research and other interesting projects!

## Banner

![Banner Animated through AES-CBC padding oracle attack](img/padding-oracle.gif)

When you visit [my website](https://pascscha.ch), you're greeted with an unconventional banner - a diagram of AES in CBC mode, animated to reveal my name letter by letter. While I must admit that the green hacker font is a bit flashy, there is some actual meaningful cryptography going on under the hood. It's a live demonstration of a padding oracle attack against a (purposefully) vulnerable script I wrote that employs "Military Grade" encryption.

[Read More](https://pascscha.ch/blog/3-breaking-military-grade-encryption/)

## 🌟 Features

- **🎭 Animated Crypto Banner**: Watch as a simulated padding-oracle attack on AES-CBC reveals my name, letter by letter!
- **📚 Dynamic Blog System**: Showcasing my latest research and projects in cryptography and cyber security.
- **🧪 Interactive Demos**: Hands-on cryptography experiments for visitors to explore.
- **🔧 No Frameworks**: Pure HTML, CSS, and JavaScript for a lightweight, speedy experience.

## 🛠 Tech Stack

- HTML5
- CSS3
- Vanilla JavaScript
- PHP (for server-side includes and dynamic content)

## 📂 Project Structure

```
.
├── index.html
├── css/
│   └── main.css
├── js/
│   ├── banner/
│   │   ├── MilitaryGradeEncryptor.js
│   │   ├── paddingOracleDemo.js
│   │   └── animationHelpers.js
│   └── ...
├── blog/
│   ├── inventory.json
│   └── ...
├── img/
│   └── ...
└── php/
    └── set-http-headers.php
```

## 🚀 Getting Started

1. Clone this repository
2. Set up a local PHP server (e.g., using XAMPP or PHP's built-in server)
3. Open `index.html` in your browser
4. Explore the cryptographic wonders!

## 📚 Blog Posts

- [ETH Study Materials](https://pascscha.ch/blog/1-eth-study-materials/)
- [Finding 19 Vulnerabilities in One Messenger](https://pascscha.ch/blog/2-sharekey-cryptography-review/)
- [Breaking Military Grade Encryption to Animate my Name](https://pascscha.ch/blog/3-breaking-military-grade-encryption/)

## 🔓 The Padding Oracle Attack

The website's banner demonstrates a padding oracle attack on AES in CBC mode. This educational demo showcases how seemingly secure systems can be vulnerable to side-channel attacks. Learn more about it [here](https://pascscha.ch/blog/3-breaking-military-grade-encryption/).

## 🤝 Contributing

Contributions, issues, and feature requests are welcome! Feel free to check the [issues page](https://github.com/pascscha/website/issues) if you want to contribute.

## 📄 License

This project is licensed under the GNU General Public License v3.0 - see the [LICENSE](LICENSE) file for details.

## 📬 Contact

Pascal Schärli - [mail@pascscha.ch](mailto:mail@pascscha.ch)

Project Link: [https://github.com/pascscha/website](https://github.com/pascscha/website)

---

⭐️ If you find this project interesting, don't forget to give it a star!