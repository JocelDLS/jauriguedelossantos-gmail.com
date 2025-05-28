const passwordAccess = (loginPass, loginEye) => {
    const input = document.getElementById(loginPass);
    const iconEye = document.getElementById(loginEye);
  
    iconEye.addEventListener("click", () => {
      input.type = input.type === "password" ? "text" : "password";
      iconEye.classList.toggle("ri-eye-fill");
      iconEye.classList.toggle("ri-eye-off-fill");
    });
  };
  
  passwordAccess("password", "loginPassword");
  
  const passwordRegister = (loginPass, loginEye) => {
    const input = document.getElementById(loginPass);
    const iconEye = document.getElementById(loginEye);
  
    iconEye.addEventListener("click", () => {
      input.type = input.type === "password" ? "text" : "password";
      iconEye.classList.toggle("ri-eye-fill");
      iconEye.classList.toggle("ri-eye-off-fill");
    });
  };
  
  passwordRegister("passwordCreate", "loginPasswordCreate");
  
  const loginAcessRegister = document.getElementById("loginAccessRegister");
  const buttonRegister = document.getElementById("loginButtonRegister");
  const buttonAccess = document.getElementById("loginButtonAccess");
    
  buttonRegister.addEventListener("click", () => {
    loginAcessRegister.classList.add("active");
  });
  
  buttonAccess.addEventListener("click", () => {
    loginAcessRegister.classList.remove("active");
  });
  
  