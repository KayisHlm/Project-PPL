const _bw=document.querySelector("#basicwizard");
if(_bw){new Wizard("#basicwizard");}
const _pw=document.querySelector("#progressbarwizard");
if(_pw){new Wizard("#progressbarwizard",{progress:!0});}
const _vw=document.querySelector("#validation-wizard");
if(_vw){new Wizard("#validation-wizard",{validate:!0});}