<?php
$json_file = 'assets/questions.json';
$json_data = file_get_contents($json_file);
$questions = json_decode($json_data, true);
?>
<div class="generic-title text-center">
    <img style = "width:100px" src="assets/images/gifs/identification.gif" alt="line">
    <span class="small-text">Hello, <b id = "name"> </b></span>
    <h2>Please Choose a topic</h2>
</div>
<?php foreach ($questions as $key=>$item){ ?>
<div class = "box" data-active = "<?= $key ?>">
    <p><?= $item['topic'] ?></p>
    <i id="t_<?= $key ?>" class="myicon fa fa-check"></i>
</div>
<?php } ?>

<button  class="btn" type="button" id="continue">
    Continue
</button>

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
        width: 100%;
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
</style>
<script>
    let act2 = undefined;
    let name = localStorage.getItem('name');
    name = JSON.parse(name)[0].name;
    name = name.charAt(0).toUpperCase() + name.slice(1);
    document.getElementById('name').innerText = name;
    $(".myicon").hide();
    let tanswers = localStorage.getItem('answers');
    console.log(tanswers);
if(tanswers != null){
        tanswers = JSON.parse(tanswers);
        for(let i = 0; i < tanswers.length; i++){
            $("#t_"+tanswers[i].topic).show();
        }
}

$("#continue").click(function(){
    if(act2 === undefined){
        alert('Please select a topic');
    }else{
        localStorage.setItem('topic', act2);
        $("#loaddata").empty();
        loadquestions();
    }
});
$(".box").click(function(){
    $(".box").css('border-color', '#cccccc');
    $(this).css('border-color', 'var(--accent)');
    act2 = $(this).data('active');
    console.log(act2);
});
</script>