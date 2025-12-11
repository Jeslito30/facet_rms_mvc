document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const roomId = urlParams.get('roomId');

    if (roomId) {
        document.getElementById('roomId').value = roomId;
        fetchRoomDetails(roomId);
    }
});

async function fetchRoomDetails(roomId) {
    try {
        const response = await fetch(`/facet-rms/public/room/get_all_api`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const result = await response.json();

        if (result.success && result.data) {
            const room = result.data.find(r => r.id == roomId);
            if (room) {
                document.getElementById('buildingInput').value = room.building;
                document.getElementById('roomInput').value = room.name;
                // You can also populate other fields like room image, etc.
                document.getElementById('roomName').textContent = room.name;
                document.getElementById('roomLocation').textContent = `${room.building}, ${room.floor}`;
                document.getElementById('roomImage').src = room.image_url || '../assets/images/placeholder.svg';
            }
        } else {
            console.error("API Error:", result.message);
        }
    } catch (error) {
        console.error("Fetch Error:", error);
    }
}

async function submitBooking() {
    const bookingData = {
        roomId: document.getElementById('roomId').value,
        meetingTitle: document.getElementById('meetingTitleInput').value,
        date: document.getElementById('dateInput').value,
        startTime: document.getElementById('startTimeInput').value,
        endTime: document.getElementById('endTimeInput').value,
        attendees: document.getElementById('attendeesInput').value,
        recurring: document.getElementById('recurringInput').value,
        description: document.getElementById('descriptionInput').value,
    };

    try {
        const response = await fetch('/facet-rms/public/booking/create_api', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(bookingData)
        });

        const result = await response.json();

        if (result.success) {
            alert('Booking created successfully!');
            window.location.href = '/facet-rms/public/bookings';
        } else {
            alert(`Error: ${result.message}`);
        }
    } catch (error) {
        console.error('Error submitting booking:', error);
        alert('An error occurred while creating the booking.');
    }
}