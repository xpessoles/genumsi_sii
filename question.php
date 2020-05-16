<li>
    <b>Question n°<?= $numero_q ?> <span style='font-size: 0.8em'>(Réf : #<?= $question['num_question'] ?>)</span> :</b>
    <?php $numero_q++; ?>
    <div class="texte-question">
        <?= $question['question'] ?>
    </div>
    <?php
    if (!is_null($question['image'])) :
    ?>
    
    <a class='a-question' id="image_question_<?= $question['num_question'] ?>" href="image_questions/<?= $question['image'] ?>">
        <img class='img-question' src="image_questions/<?= $question['image'] ?>">
    </a>

    <?php endif;

    for ($i = 0; $i < 4; $i++) : ?>
        <div class='input-group'>
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <input type="radio" name="reponse<?= $question['num_question'] ?>" value="<?= $ordres_rep[$numero_q - 2][$i] ?>">
                </div>
            </div>
            <span class='form-control'><?= $question['reponse' . $ordres_rep[$numero_q - 2][$i]] ?></span>
        </div>

        <br>

    <?php endfor ?>

    <div class='input-group'>
        <div class="input-group-prepend">
            <div class="input-group-text">
                <input type="radio" name="reponse<?= $question['num_question'] ?>" checked value="none">
            </div>
        </div>
        <span class='form-control'>Je ne sais pas...</span>
    </div>

    <br>

</li>

<br>

<script>
    $(document).ready(function() {
        $(".a-question").each(function() {
            $("#" + this.id).fancybox({
                helpers: {
                    title: {
                        type: 'inside'
                    }
                }
            })
        })

        //render_md_math()
    })
</script>