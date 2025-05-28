const userBtn = document.querySelector('#user-btn');
userBtn.addEventListener('click', function(){
    const profileBox = document.querySelector('.profile-detail');
    profileBox.classList.toggle('active');
});

const toggle = document.querySelector('.toggle-btn');
toggle.addEventListener('click', function(){
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('active');
});