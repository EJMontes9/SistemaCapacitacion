<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Certificado</title>
<style>
@page { margin: 28px; }
body { margin: 0; padding: 0; font-family: 'Times New Roman', Times, serif; font-size: 14pt; }
.outer { border: 4px solid #1a2a6c; padding: 3px; }
.inner { border: 1px solid #b38728; padding: 16px 30px; }

.corner-tr { position: fixed; top: 0; left: 0; width: 0; height: 0; border: 70px solid transparent; border-top-color: #1a2a6c; border-left-color: #1a2a6c; }
.corner-br { position: fixed; bottom: 0; right: 0; width: 0; height: 0; border: 70px solid transparent; border-bottom-color: #b38728; border-right-color: #b38728; }

h1 { font-size: 36pt; color: #1a2a6c; margin: 0 0 4pt; letter-spacing: 2pt; text-align: center; }
.subtitle { font-size: 13pt; color: #b38728; text-align: center; text-transform: uppercase; letter-spacing: 3pt; margin-bottom: 20pt; }
.present { font-size: 13pt; color: #555; text-align: center; font-style: italic; margin-bottom: 4pt; }
.student { font-size: 30pt; font-weight: bold; color: #222; text-align: center; border-bottom: 2px solid #b38728; display: block; padding-bottom: 4pt; margin: 10pt auto 18pt; width: 550pt; }
.detail { font-size: 13pt; color: #444; text-align: center; line-height: 1.4; margin-bottom: 15pt; }
.course { font-weight: bold; font-size: 16pt; color: #1a2a6c; }
.meta { font-size: 10pt; color: #777; text-align: center; margin-top: 8pt; }
.meta strong { color: #333; }

table.footer { width: 100%; margin-top: 25pt; }
table.footer td { text-align: center; width: 33%; padding: 0 10pt; }
.sig-line { border-top: 1px solid #999; width: 160pt; margin: 0 auto 4pt; }
.sig-name { font-size: 12pt; font-weight: bold; color: #333; }
.sig-title { font-size: 9pt; color: #777; }
.seal { width: 75pt; height: 75pt; background: #fbf5b7; border: 2px solid #b38728; border-radius: 50%; margin: 0 auto; text-align: center; padding-top: 18pt; font-size: 8pt; font-weight: bold; color: #1a2a6c; text-transform: uppercase; line-height: 1.3; }
</style>
</head>
<body>

<div class="corner-tr"></div>
<div class="corner-br"></div>

<div class="outer">
<div class="inner">

<table width="100%"><tr>
<td style="text-align:left;width:50%;">
    <span style="display:inline-block;width:36pt;height:36pt;background:#1a2a6c;border:2px solid #b38728;border-radius:4pt;color:white;text-align:center;line-height:36pt;font-size:14pt;font-weight:bold;vertical-align:middle;">&Omega;</span>
    <span style="display:inline-block;vertical-align:middle;margin-left:6pt;">
        <span style="display:block;font-size:11pt;font-weight:bold;color:#333;text-transform:uppercase;">Universidad Altais</span>
        <span style="display:block;font-size:8pt;color:#666;">Instituto de Tecnologia</span>
    </span>
</td>
<td style="text-align:right;width:50%;">
    <span style="display:inline-block;width:36pt;height:36pt;background:#00b4d8;border-radius:4pt;color:white;text-align:center;line-height:36pt;font-size:14pt;font-weight:bold;vertical-align:middle;">C</span>
    <span style="display:inline-block;vertical-align:middle;margin-left:6pt;">
        <span style="display:block;font-size:11pt;font-weight:bold;color:#333;text-transform:uppercase;">NeuralCore AI</span>
        <span style="display:block;font-size:8pt;color:#666;">Sistemas Inteligentes</span>
    </span>
</td>
</tr></table>

<h1>Certificado de Aprobacion</h1>
<div class="subtitle">Formacion Profesional Avanzada</div>

<div class="present">Este documento certifica oficialmente que</div>
<span class="student">@studentName</span>

<div class="detail">
Ha completado y aprobado satisfactoriamente los requisitos academicos del curso:<br>
<span class="course">@courseName</span><br>
Impartido en colaboracion institucional con validez curricular.
</div>

<div class="meta">
Fecha de emision: <strong>@completionDate</strong> | Codigo: <strong>@certificateCode</strong>
</div>

<table class="footer"><tr>
<td>
    <div class="sig-line"></div>
    <div class="sig-name">Dra. Elena Rostova</div>
    <div class="sig-title">Decana - Altais</div>
</td>
<td>
    <div class="seal">IA<br>Oficial<br>Validador</div>
</td>
<td>
    <div class="sig-line"></div>
    <div class="sig-name">Ing. Marcos Vidal</div>
    <div class="sig-title">Director IA - NeuralCore</div>
</td>
</tr></table>

</div>
</div>
</body>
</html>
