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
  });
  