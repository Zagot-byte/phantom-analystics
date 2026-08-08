document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.getElementById('sidebarToggle');
    if (toggle) {
        toggle.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-collapsed');
        });
    }

    var bell = document.getElementById('notifBtn');
    var panel = document.getElementById('notifDropdown');
    if (bell && panel) {
        bell.addEventListener('click', function (e) {
            e.stopPropagation();
            panel.classList.toggle('open');
        });
        document.addEventListener('click', function (e) {
            if (!panel.contains(e.target)) {
                panel.classList.remove('open');
            }
        });
    }
});

function openModal(id) {
    var el = document.getElementById(id);
    if (el) el.classList.add('open');
}

function closeModal(id) {
    var el = document.getElementById(id);
    if (el) el.classList.remove('open');
}

function filterLogs(input) {
    var q = input.value.toLowerCase();
    var lines = document.querySelectorAll('.log-line');
    for (var i = 0; i < lines.length; i++) {
        var text = lines[i].textContent.toLowerCase();
        lines[i].style.display = text.indexOf(q) === -1 ? 'none' : '';
    }
}
