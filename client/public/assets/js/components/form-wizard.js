;(function(){
  const _bw = document.querySelector('#basicwizard');
  if(_bw){ new Wizard('#basicwizard'); }

  const _pw = document.querySelector('#progressbarwizard');
  if(_pw){ new Wizard('#progressbarwizard', { progress: true }); }

  const _vw = document.querySelector('#validation-wizard');
  if(_vw){
    const wiz = new Wizard('#validation-wizard', { validate: true });

    const header = _vw.querySelector('.form-wizard-header');
    const tabs = header ? header.querySelectorAll('a[data-bs-toggle="tab"]') : [];

    function activeIndex(){
      let idx = 0;
      tabs.forEach((a,i)=>{ if(a.classList.contains('active')) idx = i; });
      return idx;
    }

    function validateActive(){
      if(!header) return true;
      const idx = activeIndex();
      const navItem = header.querySelectorAll('.nav-item')[idx];
      if(!navItem) return true;
      const formSelector = navItem.getAttribute('data-target-form');
      if(!formSelector) return true;
      const form = document.querySelector(formSelector);
      if(!form) return true;
      if(form.checkValidity()) return true;
      form.reportValidity();
      return false;
    }

    const prevBtn = document.querySelector('.wizard .previous a');
    const nextBtn = document.querySelector('.wizard .next a');

    if(prevBtn){
      prevBtn.addEventListener('click', function(e){
        e.preventDefault();
        const idx = activeIndex();
        const prev = idx > 0 ? tabs[idx-1] : null;
        if(prev){ prev.click(); }
      });
    }

    if(nextBtn){
      nextBtn.addEventListener('click', function(e){
        e.preventDefault();
        if(!validateActive()) return;
        const idx = activeIndex();
        const next = idx < tabs.length-1 ? tabs[idx+1] : null;
        if(next){ next.click(); }
      });
    }
  }
})();