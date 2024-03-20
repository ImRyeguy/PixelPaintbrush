var arts = document.querySelectorAll(`.art`);
for (let i = 0; i < arts.length; i++)
{
    text = arts[i].innerHTML;
    arts[i].innerHTML = '';
    arts[i].classList.remove('hidden');
    let gridColors = text.split(`|`);
    var noCols = 32;
    var noRows = 16;
    arts[i].style.gridTemplateColumns = `repeat(${noCols},1fr)`;
    var grid = [];
    for(let y = 0; y < noRows; y++)
    {
        for(let x = 0; x < noCols; x++)
        {
            grid[noCols * y + x] = document.createElement(`div`);
            grid[noCols * y + x].style.gridColumn = `${x+1}`;
            grid[noCols * y + x].style.gridRow = `${y+1}`;
            grid[noCols * y + x].style.border = `1px dashed black`;
            grid[noCols * y + x].style.backgroundColor = `${gridColors[noCols * y + x]}`;
            arts[i].appendChild(grid[noCols * y + x]);
        }
    }
}