<?php
$json_file = 'assets/questions.json';
$id = $_GET['topic'];
$json_data = file_get_contents($json_file);
$question = json_decode($json_data, true);
$questions = $question[$id]['questions'];
$quiz = json_encode($questions);
?>
<?php foreach ($questions as $key=>$item){ ?>
    <div class="parent" data-id = "<?= $key ?>">
<div class="generic-title text-center">
    <img style = "width:100px" src="assets/images/gifs/<?= $id ?>.gif" alt="line">
    <span class="small-text"><?= $question[$id]['topic'] ?></span>
    <h2><?= $item['question'] ?></h2>
</div>
<?php foreach ($item['choices'] as $index=>$value){ ?>
    <div class = "box" data-active = "<?= $index ?>">
        <p><?= $value ?></p>
        <i id="tc_q_<?= $key ?>_c_<?= $index ?>" class="myicon fa fa-check"></i>

    </div>
<?php } ?>
<div class = "btn-parent">
<button  class="btn back" data-id = "<?= $key ?>" type="button" id="continue">
    back
</button>
    <button  class="btn next" data-id = "<?= $key ?>" type="button" id="continue">
        Next
    </button>
    <button  class="btn finish" data-id = "<?= $key ?>" type="button" id="continue">
        finish
    </button>
</div>
    </div>
<?php } ?>

<style>
    .box{
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 20px;
        border-width: 1px;
        border-bottom-width: 5px;
        border-color: #cccccc;
        border-style: solid;
        border-radius: 10px;
        margin-bottom: 20px;
        cursor: pointer;
    }
    .box p {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }
    .btn {
        width: 40%;
        height: 60px;
        font-weight: 700;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        background: var(--button-color);
        color: var(--primary-color);
        transition: .3s ease-in-out;
    }

    .btn:focus {
        outline: none;
    }

    .btn:hover {
        background: var(--accent);
    }
    .back{
        background-color: #333;
    }
    .btn-parent{
        display: flex;
        justify-content: space-between;
    }
</style>
<script>
    let user = localStorage.getItem('name');
    user = JSON.parse(user)[0];
    $(".parent").hide();
    $(".parent").first().show();
    $(".finish").hide();
    $(".myicon").hide();
    let next = 0, back = 0, active2 = undefined,ans=[],index, answered = false,point=0,url,data,method;
    let Quiz = `<?= $quiz ?>`;
    Quiz = JSON.parse(Quiz);

    let answers = localStorage.getItem('answers');
    if(answers !== null){
        answers = JSON.parse(answers);
        let filtered = answers.filter(function (el) {
            return el.topic == '<?= $id ?>';
        });
        if(filtered.length > 0){
            filtered = filtered[0];
            ans = filtered.answers
            $(".box").css('border-color', '#cccccc');
            $(".box[data-active = "+ans[0]+"]").css('border-color', 'var(--accent)');
            $("#tc_q_0_c_"+Quiz[0].answer).show();
            answered = true;
            active2 = ans[0];
        }
    }else{
        answers = [];
    }

    $(".box").click(function(){
        if(answered){
            return;
        }
        $(".box").css('border-color', '#cccccc');
        $(this).css('border-color', 'var(--accent)');
        active2 = $(this).data('active');
        index = $(this).closest('.parent').data('id');
        ans[index] = active2;
    });
    $(".next").click(function(){



        console.log(ans);
        if(active2 == undefined){
            alert('Please select an answer');
        }
        else{
            next = $(this).data('id');
            next = parseInt(next) + 1;
            $(".parent").hide();
            $(".parent:eq("+next+")").show();
            if(ans[next] != undefined){
                $(".box").css('border-color', '#cccccc');
                $(".box[data-active = "+ans[next]+"]").css('border-color', 'var(--accent)');
                active2 = ans[next];
            }else{
                $(".box").css('border-color', '#cccccc');
                active2 = undefined;
            }
            if(next == $(".parent").length - 1) {
                $(".finish").show();
                $(".next").hide();
            }
            if(answered){
                $("#tc_q_"+next+"_c_"+Quiz[next].answer).show();
            }
        }
    });
    $(".back").click(function(){
        back = $(this).data('id');
        back = parseInt(back) - 1;
        if (back < 0){
            back = 0;
        }
        $(".parent").hide();
        $(".parent:eq("+back+")").show();
        if(ans[back] != undefined){
            $(".box").css('border-color', '#cccccc');
            $(".box[data-active = "+ans[back]+"]").css('border-color', 'var(--accent)');
            active2 = ans[back];
        }else{
            active2 = undefined;
        }
        if(answered){

                $("#tc_q_"+back+"_c_"+Quiz[back].answer).show();

        }
    });
    $(".finish").click(function(){
        if(answered){
            window.location.reload();
            return;
        }
        console.log(ans);
        let answer = {
            topic: '<?= $id ?>',
            answers: ans
        };
        answers.push(answer);
        localStorage.setItem('answers', JSON.stringify(answers));

        $.each(ans, async function(i, value) {
            point = 0;
            if (Quiz[i].answer === value) {
                point = 1;
            }
            data = new FormData();
            data.append("pid", user.id);
            data.append("qid", i);
            data.append("tid", "<?= $id ?>");
            data.append("aid", value);
            data.append("point", point);
            console.log("DATA",data);
            method = "POST";
            url = "https://hayahafrica.com/admin/apis/save_answers.php";
            await fetch(url, {
                method: method,
                body: data
            }).then(response => response.json()).then(data => {
                console.log(data);
            }).catch(error => {
                console.error(error);
            });

        });


        window.location.reload();
    });

</script>
