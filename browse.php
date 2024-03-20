<?php

session_start();
include __DIR__ . "/model/model_artdb.php";
$art = getAllArt();

?>
<!DOCTYPE html>
<html lang="en" style="height:100%">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Bubbles&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {}
    </script>
</head>
<body style="height:100%; font-family: 'Rubik Bubbles'" class="bg-zinc-700">
    <div id="nav-button" class="fixed bg-zinc-800 w-24 h-24 rounded-tl-3xl rounded-bl-3xl flex justify-center" style="right:0px;top:0;opacity:0.5;">
        <img src="images/menu.svg" alt="menu" draggable="false" (dragstart)="false;" class="w-16 h-16 mx-auto my-auto">
    </div>
    <div id="nav" class="hidden flex flex-col justify-between fixed right-0 top-0 w-24 h-full bg-zinc-800">
        <div id="home-btn" class="w-24 h-24 flex justify-center rounded-t-3xl rounded-b-3xl bg-zinc-900">
            <img src="images/home.svg" alt="brush" draggable="false" (dragstart)="false;" class="w-20 h-20 mx-auto my-auto">
        </div>
        <div id="paint-btn" class="w-24 h-24 flex justify-center rounded-t-3xl rounded-b-3xl hover:bg-zinc-900">
            <img src="images/brush.svg" alt="brush" draggable="false" (dragstart)="false;" class="w-20 h-20 mx-auto my-auto">
        </div>
        <div id="account-btn" class="w-24 h-24 flex justify-center rounded-t-3xl rounded-b-3xl hover:bg-zinc-900">
            <img src="images/person.svg" alt="brush" draggable="false" (dragstart)="false;" class="w-20 h-20 mx-auto my-auto">
        </div>
    </div>
    <div style="height:100%;width:100%" class="flex flex-wrap justify-center bg-zinc-700">
        <?php foreach ($art as $a): 
            $user = searchUser($a['userid']);
            ?>
            <div class="flex flex-col justify-center m-4" style="width:40%;height:40%">
                <div class="art hidden" style="display:grid;width:100%;height:100%;">
                    <?= $a['art'] ?>
                </div>
                <h3 class="self-center text-white text-2xl"><?= $a['artname'] ?> by <?= $user['userName'] ?></h3>
            </div>
        <?php endforeach; ?>
    </div>
    <script src='screens.js'></script>
    <script src='btns.js'></script>
    <script>
        var navBtn = document.querySelector(`#nav-button`);
        var navBar = document.querySelector(`#nav`);
        navBtn.addEventListener(`click`, () => {
            navBar.classList.toggle(`hidden`);
            navBtn.style.right = navBar.classList.contains(`hidden`) ? `0px` : `96px`;
            navBtn.style.opacity = navBar.classList.contains(`hidden`) ? `0.5` : `1`;
        });
    </script>
</body>
</html>