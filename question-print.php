<div class="question">
    <b>Question n°<?= $numero_q ?>:</b>
    <?php $numero_q++; ?>
    <div class="question-texte">
        <?= $question['question'] ?>
    </div>
    <?php

    if (!is_null($question['image'])) :
        ?>

        <img class='question-img' src="image_questions/<?= $question['image'] ?>">

    <?php endif; ?>
    <div class='reponses'>

    <?php for ($i = 0; $i < 4; $i++) : ?>
        <div>
            <span class='reponse-check-box'>&#9744;</span>
            <span class='reponse-body'><?= $question['reponse' . $ordres_rep[$numero_q - 2][$i]] ?></span>
        </div>

        <br>

    <?php endfor ?>

    <div>
        <span class='reponse-check-box'>&#9744;</span>
        <span class='reponse-body'>Je ne sais pas...</span>
    </div>
    
    </div>

    <br>

    </div>

<br>

<script>
$('document').ready(function() {
    render_md_math();
})

</script>