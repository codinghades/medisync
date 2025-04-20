async function loadUserInfo() {
    try {
      const res = await fetch('../process/getUser.php');
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const data = await res.json();
  
      if (data.success) {
        const nameDiv = document.querySelector('.name');
        if (!nameDiv) return;
  
        nameDiv.innerHTML = `
          <p class="userName">${data.firstName} ${data.lastName}</p>
          <p class="userID">${data.user_id}</p>
        `;
      } else {
        console.warn('Could not load user info');
      }
    } catch (err) {
      console.error('Error fetching user info:', err);
    }
  }
  
  async function autofillUserInfo() {
    try {
      const res = await fetch('../process/getUser.php');
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const data = await res.json();
  
      if (data.success) {
        document.getElementById('firstNameInput').value = data.firstName;
        document.getElementById('lastNameInput').value = data.lastName;
        document.getElementById('emailInput').value = data.email;
        document.getElementById('contactNumberInput').value = data.contactNumber;
      } else {
        console.warn('Could not load user info');
      }
    } catch (err) {
      console.error('Error fetching user info:', err);
    }
  }
  
  function toggleFields(isEditing) {
    const inputs = document.querySelectorAll('#firstNameInput, #lastNameInput, #emailInput, #contactNumberInput');
    const saveCancelButtons = document.querySelector('.saveCancelButtons');
    const editButton = document.querySelector('#editButton');
  
    inputs.forEach(input => {
      input.disabled = !isEditing;
    });
  
    if (isEditing) {
      saveCancelButtons.style.display = 'flex';
      editButton.style.display = 'none';
    } else {
      saveCancelButtons.style.display = 'none';
      editButton.style.display = 'block';
    }
  }
  
  document.addEventListener('DOMContentLoaded', () => {
    loadUserInfo();
    autofillUserInfo();
  
    const editButton = document.querySelector('#editButton');
    const cancelButton = document.querySelector('#cancelButton');
  
    editButton.addEventListener('click', (event) => {
      event.preventDefault();
      toggleFields(true);
    });
  
    cancelButton.addEventListener('click', (event) => {
      event.preventDefault();
      autofillUserInfo();
      toggleFields(false);
    });
  
    document.querySelector('#saveButton').addEventListener('click', (e) => {
        e.preventDefault();

        document.getElementById('accountPasswordInput').value = '';
        document.getElementById('accountPasswordInput').style.border = '';
        
        document.getElementById('passwordError').textContent = '';  
        document.getElementById('emailError').textContent = '';
        
        document.getElementById('accountPasswordOverlay').style.display = 'flex';
      });
  
    document.getElementById('cancelAccountPasswordBtn').addEventListener('click', () => {
      document.getElementById('accountPasswordOverlay').style.display = 'none';
    });
  
    document.getElementById('confirmAccountPasswordBtn').addEventListener('click', async () => {
      const password = document.getElementById('accountPasswordInput').value;
      const firstName = document.getElementById('firstNameInput').value;
      const lastName = document.getElementById('lastNameInput').value;
      const email = document.getElementById('emailInput').value;
      const contactNumber = document.getElementById('contactNumberInput').value;
  
      const passwordError = document.getElementById('passwordError');
      const emailError = document.getElementById('emailError');
      
      if (passwordError && emailError) {
        passwordError.textContent = '';
        emailError.textContent = '';
      }
  
      const formData = new FormData();
      formData.append('password', password);
      formData.append('firstName', firstName);
      formData.append('lastName', lastName);
      formData.append('email', email);
      formData.append('contactNumber', contactNumber);
  
      try {
        const res = await fetch('../process/updateUserInfo.php', {
          method: 'POST',
          body: formData
        });
  
        const data = await res.json();
  
        if (data.success) {
            document.getElementById('accountPasswordOverlay').style.display = 'none';
            document.getElementById('accountSuccessOverlay').style.display = 'flex';
        } else {
        if (data.error === 'email_taken' && emailError) {
            emailError.textContent = 'Email is already taken.';
            setTimeout(() => {
            emailError.textContent = '';
            }, 3000);
        } else if (data.error === 'password_incorrect' && passwordError) {
            passwordError.textContent = 'Incorrect password.';
            setTimeout(() => {
            passwordError.textContent = '';
            }, 3000);
        } else {
            document.getElementById('accountPasswordInput').style.border = '1px solid red';
            document.getElementById('accountPasswordInput').value = '';
        }
        }
      } catch (err) {
        console.error('Error updating user info:', err);
      }
    });
  
    document.getElementById('confirmAccountSuccessBtn').addEventListener('click', () => {
      document.getElementById('accountSuccessOverlay').style.display = 'none';
      loadUserInfo();
      autofillUserInfo();
      toggleFields(false);
    });

    document.getElementById('changePasswordButton').addEventListener('click', (event) => {
        event.preventDefault();
    
        document.getElementById('currentPassword').value = '';
        document.getElementById('newPassword').value = '';
        document.getElementById('confirmNewPassword').value = '';
        
        document.getElementById('currentPasswordError').textContent = '';
        document.getElementById('newPasswordError').textContent = '';
        document.getElementById('confirmNewPasswordError').textContent = '';
    
        document.getElementById('changePasswordOverlay').style.display = 'flex';
    });
    
    document.getElementById('cancelPasswordChangeBtn').addEventListener('click', (event) => {
        event.preventDefault();
        document.getElementById('changePasswordOverlay').style.display = 'none';
    });
    
    document.getElementById('confirmPasswordChangeBtn').addEventListener('click', async (event) => {
        event.preventDefault();
      
        const currentPassword = document.getElementById('currentPassword').value;
        const newPassword = document.getElementById('newPassword').value;
        const confirmNewPassword = document.getElementById('confirmNewPassword').value;
      
        const currentError = document.getElementById('currentPasswordError');
        const newError = document.getElementById('newPasswordError');
        const confirmError = document.getElementById('confirmNewPasswordError');
      
        currentError.textContent = '';
        newError.textContent = '';
        confirmError.textContent = '';
      
        currentError.classList.remove('active');
        newError.classList.remove('active');
        confirmError.classList.remove('active');
      
        if (newPassword !== confirmNewPassword) {
          confirmError.textContent = 'Passwords do not match.';
          confirmError.classList.add('active');
          setTimeout(() => {
            confirmError.textContent = '';
            confirmError.classList.remove('active');
          }, 3000);
          return;
        }
      
        const formData = new FormData();
        formData.append('currentPassword', currentPassword);
        formData.append('newPassword', newPassword);
        formData.append('confirmNewPassword', confirmNewPassword);
      
        try {
          const res = await fetch('../process/changePassword.php', {
            method: 'POST',
            body: formData,
          });
      
          const data = await res.json();
      
          if (data.success) {
            document.getElementById('changePasswordOverlay').style.display = 'none';
            document.getElementById('accountSuccessOverlay').style.display = 'flex';
          } else {
            if (data.error === 'incorrect_password') {
              currentError.textContent = 'Current password is incorrect.';
              currentError.classList.add('active');
              setTimeout(() => {
                currentError.textContent = '';
                currentError.classList.remove('active');
              }, 3000);
            } else if (data.error === 'password_mismatch') {
              confirmError.textContent = 'New passwords do not match.';
              confirmError.classList.add('active');
              setTimeout(() => {
                confirmError.textContent = '';
                confirmError.classList.remove('active');
              }, 3000);
            } else {
              alert('An error occurred while changing your password. Please try again.');
            }
          }
        } catch (err) {
          console.error('Error:', err);
          alert('An error occurred while communicating with the server. Please try again.');
        }
      });
      
    
    document.getElementById('confirmAccountSuccessBtn').addEventListener('click', () => {
        document.getElementById('accountSuccessOverlay').style.display = 'none';
    });    
  });
  
  