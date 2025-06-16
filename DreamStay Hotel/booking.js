document.getElementById('bookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = e.target;
    const data = {
        name: form.name.value,
        email: form.email.value,
        room: form.room.value,
        checkin: form.checkin.value,
        checkout: form.checkout.value
    };
    // Simulate form submission (you can replace with real backend integration)
    document.getElementById('bookingMessage').innerText =
        `Thank you, ${data.name}! Your booking for a ${data.room} from ${data.checkin} to ${data.checkout} has been received. Confirmation sent to ${data.email}.`;
    form.reset();
});
// script.js
document.getElementById('adminButton').addEventListener('click', function() {
    var adminPanel = document.getElementById('adminPanel');
    
    // Toggle visibility of admin panel
    if (adminPanel.style.display === 'none') {
        adminPanel.style.display = 'block';
    } else {
        adminPanel.style.display = 'none';
    }
});

// Example logout function
function logout() {
    alert('You have been logged out.');
    // Redirect to login page or perform other actions
    window.location.href = 'login.html';
}
