<!DOCTYPE html>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>GeNumSi</title>
    <link rel="shortcut icon" type="image/x-icon" href="image/logo-genumsi.png" />
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/showdown/1.9.0/showdown.min.js"></script>
    <script src="js/custom_script.js"></script>
<style>

#conteneur-general {
    width: 100%;
    margin: auto;
}



.qcm-title {
    text-align: center;
    margin: 20px auto 20px auto;
}

.domaine-name {
    color: brown;
}

.qcm-student-identification {
    font-size: 1.1em;    
}

.qcm-notation {
    font-size: 1.2em;    
}

.qcm {
    padding: 20px;
    margin-top: 1%;
}

.question {
    border-style: solid;
    border-radius: 5px;    
    padding: 5px;
    font-size: 1.1em;    
}


.question-img  {
    max-width: 50%;
    display: block;
    margin: 10px auto 10px auto;
}

.question-texte {
    display: block;
    margin: 5px ;
}

.table_verite {
    text-align: center;
    border-collapse: collapse;
}

code {
    font-family: monospace;
    color: black;
    font-weight: bold;
    background-color: #eee;
    border-radius: 1px;
    padding: 5px;
}

pre {
    font-family: monospace;
    color: black;
    font-size: 100%;
    display: block;
    background-color: #eee;
    white-space: pre;
    margin: 1em0;
    padding: 3px5px;
    border: 3pxdoublesilver;
    overflow: hidden;
}

.reponses {
    margin-left: 30px;
}

.reponse-check-box {
    font-size: 1.1Sem;
}

.reponse-body {
    margin-left: 20px;
}


.footer {
  width: 100%;
  color: blue;
  text-align: left;
}

@media print {
    .question {page-break-inside: avoid;}
  }



</style>
</head>

<body>