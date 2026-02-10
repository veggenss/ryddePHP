const tabs = document.querySelectorAll('.tab');
const buttons = document.querySelectorAll('.tab-btn');

buttons.forEach(btn => {
    btn.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active'));
        buttons.forEach(b => b.classList.remove('active'));

        const t = document.getElementById(btn.dataset.tab);
        t.classList.add('active');
        btn.classList.add('active');
    })
});

