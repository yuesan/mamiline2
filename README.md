# Minerva

Minerva is Moodle block plugin for students.

## このプラグインについて

Minerva(ミネルバ)は、学生が自分のアクティビティをタイムライン形式で表示させ、「ふりかえり」をするための可視化プラグインです。
このプラグインは、Moodleの標準ログ(moodle-log_standardlogs)を分析し、学生ごとに自分のログをタイムライン表示させることが出来ます。
これにより、学生が過去にMoodle上で「どのような行動をしたのか」を自分自身でふりかえりすることが出来ます。 
例えば、

*「2017年2月15日に、提出課題を出した」
*「2017年2月16日に、先生から提出課題のフィードバックをもらい、30点だった」
*「2017年2月16日に、小テストを受験して、合格した」
*「2017年2月16日に、小テストを受験して、不合格でした。もう一度受験しませんか？」 

といった情報とアドバイスをTwitterやFacebookのタイムラインのように表示させることが出来ます。
このプラグインは、「学生の学習意欲(モチベーション)を維持・向上させる」ことにあります。
残念ながら、Moodleには先生を支援する機能は豊富にありますが、学生のモチベーションを維持させる機能は少ないのが現状です。
Moodleは先生にも、学生にも親しくあるのがあるべき姿です。 ミネルバは、「学生のモチベーションを維持・向上させる」ために設計・開発されています。

## インストール方法

こちら(https://github.com/yuesan/moodle-block_minerva/releases)から最新版をダウンロードし、blockとしてインストールしてください。
インストール後、サイトトップかコースページにブロック「ミネルバ」を追加すると、起動用のボタンが表示されます。

## License
GPL V3

## 使用しているライブラリ

* bootstrap3 (MIT License)
* chart.js (MIT License)

### MIT License
#### chart.js

The MIT License (MIT)

Copyright (c) 2013-2017 Nick Downie

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

#### bootstrap3

The MIT License (MIT)

Copyright (c) 2011-2016 Twitter, Inc.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.