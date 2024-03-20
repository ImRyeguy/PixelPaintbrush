<?php
include __DIR__ . '/model/model_artdb.php';

session_start();

if (isset($_SESSION['userid']))
{
    if (isset($_POST['name']))
    {
        $accountid = $_SESSION['userid'];
        $artname = filter_input(INPUT_POST, 'name');
        $art = filter_input(INPUT_POST, 'grid');

        $result = addArt($accountid, $artname, $art);
    }
} else {
    header('Location: account.php');
}
?>
<!DOCTYPE html>
<html lang="en" style="height:100%">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pixel Paintbrush</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Bubbles&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {}
    </script>
    <style>
        img {
            -drag: none;
            user-select: none;
            -moz-user-select: none;
            -webkit-user-drag: none;
            -webkit-user-select: none;
            -ms-user-select: none;
         }
    </style>
</head>
<body style="height:100%; font-family: 'Rubik Bubbles'">
    <div id="screen" style="display:grid;height: 100%;">
    </div>
    <div id="nav-button" class="absolute bg-zinc-800 w-24 h-24 rounded-tl-3xl rounded-bl-3xl flex justify-center" style="right:0px;top:0;opacity:0.5;">
        <img src="images/menu.svg" alt="menu" draggable="false" (dragstart)="false;" class="w-16 h-16 mx-auto my-auto">
    </div>
    <div id="nav" class="hidden flex flex-col justify-between absolute right-0 top-0 w-24 h-full bg-zinc-800">
        <div id="home-btn" class="w-24 h-24 flex justify-center rounded-t-3xl rounded-b-3xl hover:bg-zinc-900">
            <img src="images/home.svg" alt="brush" draggable="false" (dragstart)="false;" class="w-20 h-20 mx-auto my-auto">
        </div>
        <div id="paint-btn" class="w-24 h-24 flex justify-center rounded-t-3xl rounded-b-3xl bg-zinc-900">
            <img src="images/brush.svg" alt="brush" draggable="false" (dragstart)="false;" class="w-20 h-20 mx-auto my-auto">
        </div>
        <div id="account-btn" class="w-24 h-24 flex justify-center rounded-t-3xl rounded-b-3xl hover:bg-zinc-900">
            <img src="images/person.svg" alt="brush" draggable="false" (dragstart)="false;" class="w-20 h-20 mx-auto my-auto">
        </div>
    </div>
    <div id="palette-button" class="absolute bg-zinc-800 w-24 h-24 rounded-tr-3xl rounded-br-3xl flex justify-center" style="left:0px;top:0;opacity:0.5;">
        <img src="images/brush.svg" alt="brush" draggable="false" (dragstart)="false;" class="w-16 h-16 mx-auto my-auto">
    </div>
    <div id="palette" class="hidden flex flex-col justify-between absolute left-0 top-0 w-24 h-full bg-zinc-800">
        <div>
            <div id="color-selector" class="w-24 h-24 flex justify-center rounded-t-3xl rounded-b-3xl hover:bg-zinc-900">
                <img src="images/pallete.svg" alt="brush" draggable="false" (dragstart)="false;" class="w-20 h-20 mx-auto my-auto">
            </div>
            <div id="color-selector-dd" class="hidden flex w-24 h-24 justify-center rounded-b-3xl">
                <input type="color" id="color-picker" class="w-16 h-16"/>
            </div>
        </div>
        <div id="save-btn" class="w-24 h-24 flex justify-center rounded-l-3xl rounded-r-3xl hover:bg-zinc-900">
            <img src="images/save.svg" alt="brush" draggable="false" (dragstart)="false;" class="w-16 h-16 mx-auto my-auto">
        </div>
        <div id="save-form" class="hidden absolute left-24 bottom-0 w-48 h-24 bg-zinc-800">
            <form name="save" action="index.php" method="POST">
                <input type="text" id="name-inp" name="name" placeholder="Name" class="w-48 h-12 bg-zinc-900 text-white text-center">
                <input id="grid-inp" type="text" name="grid" value="" hidden>
                <input type="submit" value="Save" class="w-48 h-12 bg-zinc-900 text-white text-center">
            </form>
        </div>
    </div>
    <script src='btns.js'></script>
    <script>
        var color = `#000000`;
        var noCols = 32;
        var noRows = 16;
        var screenGrid = document.querySelector(`#screen`);
        screenGrid.style.gridTemplateColumns = `repeat(${noCols},1fr)`;
        var grid = [];
        for(let y = 0; y < noRows; y++)
        {
            for(let x = 0; x < noCols; x++)
            {
                grid[noCols * y + x] = document.createElement(`div`);
                grid[noCols * y + x].style.gridColumn = `${x+1}`;
                grid[noCols * y + x].style.gridRow = `${y+1}`;
                grid[noCols * y + x].style.border = `1px dashed black`;
                grid[noCols * y + x].style.backgroundColor = `lightblue`;
                grid[noCols * y + x].addEventListener(`click`, () => {
                    grid[noCols * y + x].style.backgroundColor = `${color}`;
                });
                screenGrid.appendChild(grid[noCols * y + x]);
            }
        }

        function updateGridData(grid)
        {
            let colorGrid = ``;
            for (let i = 0; i < grid.length; i++)
            {
                colorGrid += `${grid[i].style.backgroundColor}`;
                if (i != grid.length - 1)
                {
                    colorGrid += `|`
                }
            }
            return colorGrid;
        }

        function mouseDown(e)
        {
            if (e.target.id != `screen`)
            {
                e.target.style.backgroundColor = `${color}`;
            }
        }

        document.querySelector(`#name-inp`).addEventListener(`input`, () => {
            document.querySelector(`#grid-inp`).value = updateGridData(grid);
        });

        screenGrid.addEventListener(`mousedown`, () => {
            screenGrid.addEventListener(`mouseover`, mouseDown);
        });

        screenGrid.addEventListener(`mouseup`, () => {
            screenGrid.removeEventListener(`mouseover`, mouseDown);
        });

        function getRowColIndex(row, col)
        {
            return noCols * row + col;
        }

        var navBtn = document.querySelector(`#nav-button`);
        var navBar = document.querySelector(`#nav`);
        navBtn.addEventListener(`click`, () => {
            navBar.classList.toggle(`hidden`);
            navBtn.style.right = navBar.classList.contains(`hidden`) ? `0px` : `96px`;
            navBtn.style.opacity = navBar.classList.contains(`hidden`) ? `0.5` : `1`;
        });

        function navFollowMouse(e)
        {
            console.log(e.clientY);
            if (e.clientY < 48)
            {
                navBtn.style.top = `0px`;
                return;
            }
            else if (e.clientY > window.innerHeight - 48)
            {
                navBtn.style.top = `${window.innerHeight-96}px`;
                return;
            }
            else
            {
                navBtn.style.top = `${e.clientY-48}px`;
            }
        }

        navBtn.addEventListener(`mousedown`, () => {
            navBtn.addEventListener(`mousemove`, navFollowMouse);
        });

        navBtn.addEventListener(`mouseup`, () => {
            navBtn.removeEventListener(`mousemove`, navFollowMouse);
        });
        
        var palBtn = document.querySelector(`#palette-button`);
        var palBar = document.querySelector(`#palette`);
        palBtn.addEventListener(`click`, () => {
            palBar.classList.toggle(`hidden`);
            palBtn.style.left = palBar.classList.contains(`hidden`) ? `0px` : `96px`;
            palBtn.style.opacity = palBar.classList.contains(`hidden`) ? `0.5` : `1`;
        });

        function palFollowMouse(e)
        {
            console.log(e.clientY);
            if (e.clientY < 48)
            {
                palBtn.style.top = `0px`;
                return;
            }
            else if (e.clientY > window.innerHeight - 48)
            {
                palBtn.style.top = `${window.innerHeight-96}px`;
                return;
            }
            else
            {
                palBtn.style.top = `${e.clientY-48}px`;
            }
        }

        palBtn.addEventListener(`mousedown`, () => {
            palBtn.addEventListener(`mousemove`, palFollowMouse);
        });

        palBtn.addEventListener(`mouseup`, () => {
            palBtn.removeEventListener(`mousemove`, palFollowMouse);
        });
        
        var colorSelector = document.querySelector(`#color-selector`);
        var colorSelectorDD = document.querySelector(`#color-selector-dd`);
        var colorPicker = document.querySelector(`#color-picker`);
        colorSelector.addEventListener(`click`, () => {
            colorSelectorDD.classList.toggle(`hidden`);
            colorSelector.classList.toggle(`rounded-b-3xl`);
            colorSelector.classList.toggle(`bg-zinc-900`);
            colorSelectorDD.classList.toggle(`bg-zinc-900`);
        });
        colorPicker.addEventListener(`change`, () => {
            color = colorPicker.value;
        });

        var saveBtn = document.querySelector(`#save-btn`);
        var savePO = document.querySelector(`#save-form`)
        saveBtn.addEventListener(`click`, (e) => {
            saveBtn.classList.toggle(`rounded-r-3xl`);
            saveBtn.classList.toggle(`bg-zinc-900`);
            savePO.classList.toggle(`hidden`);
        });
    </script>
</body>
</html>