// script.js
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the application
    initApp();
    
    // Set up event listeners
    setupEventListeners();
});

function initApp() {
    // Check if exercises exist in localStorage, if not initialize an empty array
    if (!localStorage.getItem('exercises')) {
        localStorage.setItem('exercises', JSON.stringify([]));
    }
    
    // Display exercises on the home page
    if (document.getElementById('exerciseTable')) {
        displayExercises();
    }
    
    // Set today's date as default in the form
    if (document.getElementById('exerciseDate')) {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('exerciseDate').value = today;
    }
}

function setupEventListeners() {
    // Exercise form submission
    const exerciseForm = document.getElementById('exerciseForm');
    if (exerciseForm) {
        exerciseForm.addEventListener('submit', handleExerciseSubmit);
    }
    
    // Cancel button in exercise form
    const cancelBtn = document.getElementById('cancelBtn');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', () => {
            window.location.href = 'index.html';
        });
    }
    
    // Contact form submission
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', handleContactSubmit);
    }
    
    // "Start Tracking" button on home page
    const startTrackingBtn = document.querySelector('[data-page="form"]');
    if (startTrackingBtn) {
        startTrackingBtn.addEventListener('click', (e) => {
            e.preventDefault();
            window.location.href = 'form.html';
        });
    }
}

function handleExerciseSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    if (!form.checkValidity()) {
        e.stopPropagation();
        form.classList.add('was-validated');
        return;
    }
    
    // Get form values
    const exercise = {
        id: document.getElementById('exerciseId').value || Date.now().toString(),
        name: document.getElementById('exerciseName').value,
        duration: parseInt(document.getElementById('exerciseDuration').value),
        calories: parseInt(document.getElementById('exerciseCalories').value),
        date: document.getElementById('exerciseDate').value
    };
    
    // Save the exercise
    saveExercise(exercise);
    
    // Redirect to home page
    window.location.href = 'index.html';
}

function saveExercise(exercise) {
    const exercises = JSON.parse(localStorage.getItem('exercises'));
    const existingIndex = exercises.findIndex(ex => ex.id === exercise.id);
    
    if (existingIndex >= 0) {
        // Update existing exercise
        exercises[existingIndex] = exercise;
    } else {
        // Add new exercise
        exercises.push(exercise);
    }
    
    localStorage.setItem('exercises', JSON.stringify(exercises));
}

function displayExercises() {
    const exercises = JSON.parse(localStorage.getItem('exercises'));
    const tableBody = document.getElementById('exerciseTable').querySelector('tbody');
    
    // Clear existing rows
    tableBody.innerHTML = '';
    
    if (exercises.length === 0) {
        const row = document.createElement('tr');
        row.innerHTML = '<td colspan="5" class="text-center">No exercises recorded yet. <a href="form.html">Add your first exercise!</a></td>';
        tableBody.appendChild(row);
        return;
    }
    
    // Sort exercises by date (newest first)
    exercises.sort((a, b) => new Date(b.date) - new Date(a.date));
    
    // Add exercises to the table
    exercises.forEach(exercise => {
        const row = document.createElement('tr');
        
        row.innerHTML = `
            <td>${exercise.name}</td>
            <td>${exercise.duration} min</td>
            <td>${exercise.calories}</td>
            <td>${formatDate(exercise.date)}</td>
            <td>
                <button class="btn btn-sm btn-warning edit-btn" data-id="${exercise.id}">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="${exercise.id}">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        
        tableBody.appendChild(row);
    });
    
    // Add event listeners to edit and delete buttons
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', handleEditExercise);
    });
    
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', handleDeleteExercise);
    });
}

function handleEditExercise(e) {
    const exerciseId = e.currentTarget.getAttribute('data-id');
    const exercises = JSON.parse(localStorage.getItem('exercises'));
    const exercise = exercises.find(ex => ex.id === exerciseId);
    
    if (exercise) {
        // Fill the form with exercise data
        document.getElementById('exerciseId').value = exercise.id;
        document.getElementById('exerciseName').value = exercise.name;
        document.getElementById('exerciseDuration').value = exercise.duration;
        document.getElementById('exerciseCalories').value = exercise.calories;
        document.getElementById('exerciseDate').value = exercise.date;
        
        // Go to the form page
        window.location.href = 'form.html';
    }
}

function handleDeleteExercise(e) {
    if (confirm('Are you sure you want to delete this exercise?')) {
        const exerciseId = e.currentTarget.getAttribute('data-id');
        const exercises = JSON.parse(localStorage.getItem('exercises'));
        const filteredExercises = exercises.filter(ex => ex.id !== exerciseId);
        
        localStorage.setItem('exercises', JSON.stringify(filteredExercises));
        displayExercises();
    }
}

function handleContactSubmit(e) {
    e.preventDefault();
    
    // In a real application, you would send this data to a server
    alert('Thank you for your message! We will get back to you soon.');
    e.target.reset();
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}