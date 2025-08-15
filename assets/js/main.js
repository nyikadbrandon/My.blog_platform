(function(){
  const root=document.documentElement;
  const saved=localStorage.getItem('theme'); if(saved){ root.setAttribute('data-theme', saved); }
  document.getElementById('themeToggle')?.addEventListener('click', ()=>{
    const cur = root.getAttribute('data-theme')==='dark' ? 'light' : 'dark';
    root.setAttribute('data-theme', cur); localStorage.setItem('theme', cur);
  });
})();
if(document.querySelector('.rte')){
  tinymce.init({selector:'.rte',plugins:'link lists code image autoresize',toolbar:'undo redo | styles | bold italic underline | bullist numlist | alignleft aligncenter alignright | link image | code',menubar:false,height:380});
}