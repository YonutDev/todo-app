// Prevent form resubmission on refresh
if(window.history.replaceState) { window.history.replaceState(null, null, window.location.href); }

function getCookie(e){let c=e+"=",d=document.cookie.split(";");for(let b=0;b<d.length;b++){let a=d[b];for(;" "==a.charAt(0);)a=a.substring(1);if(0==a.indexOf(c))return a.substring(c.length,a.length)}return""}

// Update items left from the bottom of todo list
function updateItemsLeft() {
    let items_left = 0;
    for (var i in todos) {
        (todos[i].completed === false && todos[i].disabled === false) ? items_left = items_left+1 : 0;  
    }
    $('.todo-items-left').text(items_left + " items left");
}
updateItemsLeft();

// On click todo radio
$("[id*='todo-complete']").click(function(){
    let todo_id = $(this).attr('id').replace('todo-complete-','');
    if (todos[todo_id - 1].completed === true) {
        todos[todo_id - 1].completed = false;
        if (window.location.href.indexOf("filter=completed") > -1) location.reload(); // When user is using a filter
        else {
            if (getCookie("COLOR_MODE") === 'DARK') $("#todo-" + todo_id + ' label').css({'text-decoration': 'none', 'color': 'hsl(234, 39%, 85%)'});
            else $("#todo-" + todo_id + ' label').css({'text-decoration': 'none', 'color': 'hsl(235, 19%, 35%)'});
            $(this).prop("checked", false);
        }
    } else {
        todos[todo_id - 1].completed = true;
        if (window.location.href.indexOf("filter=active") > -1) location.reload(); // When user is using a filter
        else {
            if (getCookie("COLOR_MODE") === 'DARK') $("#todo-" + todo_id + ' label').css({'text-decoration': 'line-through', 'color': 'hsl(233, 14%, 35%)'});
            else $("#todo-" + todo_id + ' label').css({'text-decoration': 'line-through', 'color': 'hsl(236, 9%, 61%)'});
        }
    }
    document.cookie = "TODOS = " + JSON.stringify(todos);
    updateItemsLeft();
});

// When user is clicking 'clear completed' from the bottom of todo list
$("#todo-clear-completed").click(function(){
    for (var i in todos) {
        if (todos[i].completed === true) {
            todos[i].disabled = true;
            document.cookie = "TODOS = " + JSON.stringify(todos);
            location.reload();
        }
    }
}); 

// On click delete
$("[id*='todo-delete']").click(function(){
    let todo_id = $(this).attr('id').replace('todo-delete-','');
    todos[todo_id - 1].disabled = true;
    document.cookie = "TODOS = " + JSON.stringify(todos);
    location.reload();
});

if ($(window).width() > 500) { // Check if user is on mobile
    // When user is hovering a todo, show the delete icon (X)
    $("[id*='todo-']").hover(
        function () {
            let todo_id = $(this).attr('id').replace('todo-','');
            $('#todo-delete-'+todo_id).css("display", "block");
        }, 
        function () {
            let todo_id = $(this).attr('id').replace('todo-','');
            $('#todo-delete-'+todo_id).css("display", "none");
        }
    );
}

// When user is clicking color mode changer
$("#color-mode-changer").click(function(){
    if (getCookie("COLOR_MODE") === "" || getCookie("COLOR_MODE") === "LIGHT") document.cookie = "COLOR_MODE = DARK";
    else if (getCookie("COLOR_MODE") === "DARK") document.cookie = "COLOR_MODE = LIGHT";
    location.reload();
});