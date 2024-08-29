$(document).ready(function () {
    // Compile Handlebars templates
    var todoTemplate = Handlebars.compile($("#todo-template").html());
    var footerTemplate = Handlebars.compile($("#footer-template").html());

    function fetchTodos() {
        $.get("/todos", function (data) {
            $("#todo-list").html(todoTemplate(data));
            $("#main").show();
            updateFooter(data);
        });
    }

    function updateFooter(todos) {
        var activeCount = todos.filter((todo) => !todo.completed).length;
        var filter = window.location.hash.replace("#/", "") || "all";
        var completedTodos = todos.some((todo) => todo.completed);
        $("#footer").html(
            footerTemplate({
                activeTodoCount: activeCount,
                activeTodoWord: activeCount === 1 ? "item" : "items",
                filter: filter,
                completedTodos: completedTodos,
            })
        );
    }

    $("#new-todo").on("keyup", function (e) {
        if (e.key === "Enter") {
            var title = $(this).val();
            if (title.trim()) {
                $.post("/todos", { title: title }, function () {
                    $("#new-todo").val("");
                    fetchTodos();
                });
            }
        }
    });

    $("#todo-list").on("change", ".toggle", function () {
        var id = $(this).closest("li").data("id");
        var completed = $(this).is(":checked");
        $.ajax({
            url: "/todos/" + id,
            method: "PUT",
            data: { completed: completed },
            success: fetchTodos,
        });
    });

    $("#todo-list").on("dblclick", "label", function () {
        var $li = $(this).closest("li");
        $li.addClass("editing");
        $li.find(".edit").focus();
    });

    $("#todo-list").on("keyup", ".edit", function (e) {
        if (e.key === "Enter") {
            var id = $(this).closest("li").data("id");
            var title = $(this).val();
            $.ajax({
                url: "/todos/" + id,
                method: "PUT",
                data: { title: title },
                success: function () {
                    fetchTodos();
                },
            });
        }
    });

    $("#todo-list").on("click", ".destroy", function () {
        var id = $(this).closest("li").data("id");
        $.ajax({
            url: "/todos/" + id,
            method: "DELETE",
            success: fetchTodos,
        });
    });

    $("#filters").on("click", "a", function () {
        window.location.hash = $(this).attr("href").substring(1);
        fetchTodos();
    });

    $("#footer").on("click", ".clear-completed", function () {
        $.get("/todos", function (todos) {
            var completedTodos = todos.filter((todo) => todo.completed);
            completedTodos.forEach((todo) => {
                $.ajax({
                    url: "/todos/" + todo.id,
                    method: "DELETE",
                });
            });
            fetchTodos();
        });
    });

    // Initial fetch
    fetchTodos();
});
