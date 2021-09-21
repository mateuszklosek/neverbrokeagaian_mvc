function showPwd(id, el) {
  let x = document.getElementById(id);
  if (x.type === "password") {
    x.type = "text";
    el.className = 'demo-icon icon-eye-off';
  } else {
    x.type = "password";
    el.className = 'demo-icon icon-eye';
  }
} 


