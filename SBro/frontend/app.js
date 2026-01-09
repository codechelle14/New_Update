// SpotBro App - Main JavaScript File

// Sample Data
const exercises = [
    { id: 1, name: 'Squat', difficulty: 'Beginner', muscles: 'Legs, Glutes, Core', image: '🏋️', reps: 12 },
    { id: 2, name: 'Push-up', difficulty: 'Beginner', muscles: 'Chest, Triceps, Shoulders', image: '💪', reps: 15 },
    { id: 3, name: 'Plank', difficulty: 'Intermediate', muscles: 'Core, Shoulders', image: '🧘', reps: 30 },
    { id: 4, name: 'Lunge', difficulty: 'Beginner', muscles: 'Legs, Glutes', image: '🦵', reps: 10 },
];

const recentWorkouts = [
    { date: '2025-11-20', exercise: 'Squat', score: 87, reps: 12 },
    { date: '2025-11-19', exercise: 'Push-up', score: 92, reps: 15 },
    { date: '2025-11-18', exercise: 'Plank', score: 78, reps: 30 },
];

let selectedExercise = null;

// Authentication
function login() {
    // Hide login page
    document.getElementById('loginPage').classList.add('hidden');
    
    // Show navigation
    document.getElementById('navigation').classList.remove('hidden');
    
    // Show dashboard
    showPage('dashboard');
}

function logout() {
    // Hide all pages that exist
    const pages = ['dashboardPage', 'libraryPage', 'detailPage', 'cameraSetupPage', 'workoutActivePage', 'summaryPage', 'progressPage'];
    pages.forEach(page => {
        const element = document.getElementById(page);
        if (element) {
            element.classList.add('hidden');
        }
    });

    // Hide navigation if it exists
    const navigation = document.getElementById('navigation');
    if (navigation) {
        navigation.classList.add('hidden');
    }

    // Show login page if it exists
    const loginPage = document.getElementById('loginPage');
    if (loginPage) {
        loginPage.classList.remove('hidden');
    }
}

// Page Navigation
function showPage(pageName) {
    // Hide all pages that exist in the current document
    const pages = ['dashboardPage', 'libraryPage', 'detailPage', 'cameraSetupPage', 'workoutActivePage', 'summaryPage', 'progressPage'];
    pages.forEach(page => {
        const element = document.getElementById(page);
        if (element) {
            element.classList.add('hidden');
        }
    });

    // Remove active class from all nav items
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });

    // Show selected page
    const pageMap = {
        'dashboard': 'dashboardPage',
        'library': 'libraryPage',
        'detail': 'detailPage',
        'cameraSetup': 'cameraSetupPage',
        'workoutActive': 'workoutActivePage',
        'summary': 'summaryPage',
        'progress': 'progressPage'
    };

    const targetPage = document.getElementById(pageMap[pageName]);
    if (targetPage) {
        targetPage.classList.remove('hidden');
    }

    // Set active nav item
    if (pageName === 'dashboard') {
        const navDashboard = document.getElementById('navDashboard');
        if (navDashboard) navDashboard.classList.add('active');
    } else if (pageName === 'library') {
        const navLibrary = document.getElementById('navLibrary');
        if (navLibrary) navLibrary.classList.add('active');
    } else if (pageName === 'progress') {
        const navProgress = document.getElementById('navProgress');
        if (navProgress) navProgress.classList.add('active');
    }

    // Load page content
    if (pageName === 'dashboard') {
        loadDashboard();
    } else if (pageName === 'library') {
        loadExerciseLibrary();
    } else if (pageName === 'detail') {
        loadExerciseDetail();
    } else if (pageName === 'summary') {
        loadWorkoutSummary();
    } else if (pageName === 'progress') {
        loadProgressDashboard();
    }
}

// Dashboard
function loadDashboard() {
    const container = document.getElementById('recentWorkouts');
    container.innerHTML = '';
    
    recentWorkouts.forEach(workout => {
        const workoutCard = `
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition cursor-pointer">
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-100 p-3 rounded-lg">
                        <svg class="icon text-blue-600" viewBox="0 0 24 24">
                            <path d="m6.5 6.5 11 11"></path>
                            <path d="m21 21-1-1"></path>
                            <path d="m3 21 9-9"></path>
                            <circle cx="10.5" cy="10.5" r="7.5"></circle>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">${workout.exercise}</p>
                        <p class="text-sm text-gray-500">${workout.date}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-blue-600">${workout.score}%</p>
                    <p class="text-sm text-gray-500">${workout.reps} reps</p>
                </div>
            </div>
        `;
        container.innerHTML += workoutCard;
    });
}

// Exercise Library
function loadExerciseLibrary() {
    const container = document.getElementById('exerciseGrid');
    container.innerHTML = '';
    
    exercises.forEach(exercise => {
        const difficultyColor = exercise.difficulty === 'Beginner' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
        
        const exerciseCard = `
            <button onclick="selectExercise(${exercise.id})" class="card bg-white rounded-2xl p-6 shadow-sm hover:shadow-xl text-left">
                <div class="text-6xl mb-4 text-center">${exercise.image}</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">${exercise.name}</h3>
                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Difficulty:</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold ${difficultyColor}">
                            ${exercise.difficulty}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">${exercise.muscles}</p>
                </div>
                <div class="mt-4 flex items-center text-blue-600 font-semibold">
                    <span>Start Exercise</span>
                    <svg class="icon ml-1" viewBox="0 0 24 24">
                        <path d="m9 18 6-6-6-6"></path>
                    </svg>
                </div>
            </button>
        `;
        container.innerHTML += exerciseCard;
    });
}

function selectExercise(exerciseId) {
    selectedExercise = exercises.find(ex => ex.id === exerciseId);
    showPage('detail');
}

// Exercise Detail
function loadExerciseDetail() {
    if (!selectedExercise) return;
    
    const container = document.getElementById('exerciseDetail');
    container.innerHTML = `
        <div class="text-center mb-8">
            <div class="text-8xl mb-4">${selectedExercise.image}</div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">${selectedExercise.name}</h1>
            <p class="text-gray-600">Target: ${selectedExercise.muscles}</p>
        </div>

        <div class="space-y-6">
            <div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">How to Perform</h3>
                <ul class="space-y-2 text-gray-700">
                    <li class="flex items-start space-x-2">
                        <span class="text-blue-600 font-bold">1.</span>
                        <span>Stand with feet shoulder-width apart</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="text-blue-600 font-bold">2.</span>
                        <span>Keep your back straight and core engaged</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="text-blue-600 font-bold">3.</span>
                        <span>Lower your body until thighs are parallel to ground</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="text-blue-600 font-bold">4.</span>
                        <span>Push through heels to return to starting position</span>
                    </li>
                </ul>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                <h4 class="font-bold text-yellow-900 mb-2">Common Mistakes</h4>
                <ul class="space-y-1 text-yellow-800 text-sm">
                    <li>• Knees caving inward</li>
                    <li>• Lifting heels off the ground</li>
                    <li>• Leaning too far forward</li>
                </ul>
            </div>
        </div>

        <button onclick="showPage('cameraSetup')" class="btn w-full mt-8 gradient-button text-white py-4 rounded-xl font-semibold text-lg flex items-center justify-center space-x-2">
            <svg class="icon" viewBox="0 0 24 24">
                <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"></path>
                <circle cx="12" cy="13" r="3"></circle>
            </svg>
            <span>Start Exercise</span>
        </button>
    `;
    
    // Update current exercise name in workout active page
    if (selectedExercise) {
        document.getElementById('currentExerciseName').textContent = `Exercise: ${selectedExercise.name}`;
    }
}

// Workout Summary
function loadWorkoutSummary() {
    const container = document.getElementById('repBreakdown');
    const scores = [95, 89, 92, 87, 84, 90, 88, 85, 91, 86, 83, 88];
    
    container.innerHTML = '';
    scores.forEach((score, idx) => {
        const colorClass = score >= 90 ? 'bg-green-500' : score >= 80 ? 'bg-yellow-500' : 'bg-red-500';
        
        const repRow = `
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Rep ${idx + 1}</span>
                <div class="flex items-center space-x-2">
                    <div class="w-32 bg-gray-200 rounded-full h-2">
                        <div class="${colorClass} h-2 rounded-full" style="width: ${score}%"></div>
                    </div>
                    <span class="font-semibold text-gray-900 w-12">${score}%</span>
                </div>
            </div>
        `;
        container.innerHTML += repRow;
    });
}

// Progress Dashboard
function loadProgressDashboard() {
    // Load Form Score Trend Chart
    const formScoreData = [65, 70, 68, 75, 78, 82, 80, 85, 87, 89, 92, 90];
    const formScoreContainer = document.getElementById('formScoreChart');
    formScoreContainer.innerHTML = '';
    
    formScoreData.forEach((score, idx) => {
        const bar = `
            <div class="flex-1 flex flex-col items-center">
                <div class="chart-bar w-full rounded-t-lg" 
                     style="height: ${(score / 100) * 100}%; background: linear-gradient(to top, #667eea, #764ba2);">
                </div>
                <span class="text-xs text-gray-500 mt-2">W${idx + 1}</span>
            </div>
        `;
        formScoreContainer.innerHTML += bar;
    });
    
    // Load Workout Frequency Chart
    const frequencyData = [3, 4, 2, 5, 4, 6, 5];
    const frequencyContainer = document.getElementById('workoutFrequencyChart');
    const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    frequencyContainer.innerHTML = '';
    
    frequencyData.forEach((count, idx) => {
        const bar = `
            <div class="flex-1 flex flex-col items-center">
                <div class="chart-bar w-full rounded-t-lg" 
                     style="height: ${(count / 6) * 100}%; background: linear-gradient(to top, #22c55e, #4ade80);">
                </div>
                <span class="text-xs text-gray-500 mt-2">${days[idx]}</span>
            </div>
        `;
        frequencyContainer.innerHTML += bar;
    });
    
    // Load Exercise Breakdown
    const breakdownContainer = document.getElementById('exerciseBreakdown');
    breakdownContainer.innerHTML = '';
    
    exercises.forEach(exercise => {
        const sessions = Math.floor(Math.random() * 20 + 10);
        const breakdownCard = `
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                <div class="flex items-center space-x-4">
                    <div class="text-3xl">${exercise.image}</div>
                    <div>
                        <p class="font-semibold text-gray-900">${exercise.name}</p>
                        <p class="text-sm text-gray-500">Last 7 days</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-gray-900">${sessions}</p>
                    <p class="text-sm text-gray-500">sessions</p>
                </div>
            </div>
        `;
        breakdownContainer.innerHTML += breakdownCard;
    });
}

// Initialize app
document.addEventListener('DOMContentLoaded', function() {
    // App starts at login page by default
    console.log('SpotBro App Initialized');
});

// SpotBro App - Main JavaScript File

// ... your existing code ...

// Password Field Handling (add this function)
function disableBrowserPasswordSuggestions() {
    // Run this when signup or login pages are loaded
    const passwordFields = document.querySelectorAll('input[type="password"], input[name="password"], input[name="confirm_password"]');
    
    passwordFields.forEach(function(field) {
        // Multiple methods to disable browser features
        field.setAttribute('autocomplete', 'new-password');
        field.setAttribute('autocorrect', 'off');
        field.setAttribute('spellcheck', 'false');
        field.setAttribute('autocapitalize', 'off');
        
        // Set properties directly
        field.autocomplete = 'new-password';
        field.autocorrect = 'off';
        
        // Change to text type initially to prevent browser detection
        if (!field.hasAttribute('data-processed')) {
            field.setAttribute('data-original-type', field.type);
            field.type = 'text';
            field.setAttribute('data-processed', 'true');
            
            // Change back to password on focus
            field.addEventListener('focus', function() {
                if (this.type === 'text') {
                    this.type = 'password';
                }
            });
            
            // Optional: Change back to text on blur if empty
            field.addEventListener('blur', function() {
                if (this.value === '' && this.type === 'password') {
                    this.type = 'text';
                }
            });
        }
    });
}

// Add to your existing initialization
document.addEventListener('DOMContentLoaded', function() {
    // App starts at login page by default
    console.log('SpotBro App Initialized');
    
    // Initialize password field handling
    disableBrowserPasswordSuggestions();
    
    // Also run on any page changes (for SPA-like behavior)
    observePageChanges();
});

// Function to observe page changes (for your single-page app)
function observePageChanges() {
    // If you're using a Single Page Application pattern
    // This will re-run when content changes
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                // Check if any new password fields were added
                disableBrowserPasswordSuggestions();
            }
        });
    });
    
    // Observe the entire document for changes
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
}

// Optional: Add a utility function to toggle password visibility
function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    if (field) {
        if (field.type === 'password') {
            field.type = 'text';
        } else {
            field.type = 'password';
        }
    }
}

// Optional: Add password strength indicator (without suggestions)
function checkPasswordStrength(password) {
    if (!password) return '';
    
    const hasMinLength = password.length >= 12;
    const hasUpperCase = /[A-Z]/.test(password);
    const hasLowerCase = /[a-z]/.test(password);
    const hasNumbers = /[0-9]/.test(password);
    
    const strength = hasMinLength && hasUpperCase && hasLowerCase && hasNumbers;
    
    return {
        isValid: strength,
        hasMinLength,
        hasUpperCase,
        hasLowerCase,
        hasNumbers
    };
}

// Add this to your signup form validation if needed
function validatePasswordOnInput() {
    const passwordField = document.querySelector('input[name="password"]');
    const confirmField = document.querySelector('input[name="confirm_password"]');
    
    if (passwordField) {
        passwordField.addEventListener('input', function() {
            const strength = checkPasswordStrength(this.value);
            
            // You could update UI indicators here
            // Example: show checkmarks or Xs for requirements
            updatePasswordRequirementsUI(strength);
        });
    }
    
    if (confirmField && passwordField) {
        confirmField.addEventListener('input', function() {
            const match = this.value === passwordField.value;
            updatePasswordMatchUI(match);
        });
    }
}

function updatePasswordRequirementsUI(strength) {
    // Update UI elements to show requirements
    const indicators = {
        length: document.querySelector('.req-length'),
        upper: document.querySelector('.req-upper'),
        lower: document.querySelector('.req-lower'),
        number: document.querySelector('.req-number')
    };
    
    if (indicators.length) {
        indicators.length.style.color = strength.hasMinLength ? 'green' : 'red';
    }
    if (indicators.upper) {
        indicators.upper.style.color = strength.hasUpperCase ? 'green' : 'red';
    }
    if (indicators.lower) {
        indicators.lower.style.color = strength.hasLowerCase ? 'green' : 'red';
    }
    if (indicators.number) {
        indicators.number.style.color = strength.hasNumbers ? 'green' : 'red';
    }
}

function updatePasswordMatchUI(match) {
    const matchIndicator = document.querySelector('.req-match');
    if (matchIndicator) {
        matchIndicator.style.color = match ? 'green' : 'red';
    }
}

// In your app.js file, add or update these functions:

// Progress Dashboard - MODIFIED TO USE BACKEND DATA
function loadProgressDashboard() {
    console.log('Loading progress dashboard with backend data...');
    
    // Use the data from backend (passed via window.progressData)
    const progressData = window.progressData || getFallbackProgressData();
    
    // 1. Load Form Score Trend Chart
    const formScoreData = progressData.form_trend?.scores || [65, 70, 68, 75, 78, 82, 80, 85, 87, 89, 92, 90];
    const formScoreLabels = progressData.form_trend?.weeks || ['W1', 'W2', 'W3', 'W4', 'W5', 'W6', 'W7', 'W8', 'W9', 'W10', 'W11', 'W12'];
    loadFormScoreChart(formScoreData, formScoreLabels);
    
    // 2. Load Workout Frequency Chart
    const frequencyData = progressData.weekly_frequency?.counts || [3, 4, 2, 5, 4, 6, 5];
    const dayLabels = progressData.weekly_frequency?.days || ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    loadWorkoutFrequencyChart(frequencyData, dayLabels);
    
    // 3. Load Exercise Breakdown
    const exerciseNames = progressData.exercise_breakdown?.exercises || ['Squat', 'Push-up', 'Plank', 'Lunge', 'Sit-up'];
    const exerciseCounts = progressData.exercise_breakdown?.counts || [15, 12, 8, 10, 6];
    loadExerciseBreakdown(exerciseNames, exerciseCounts);
}

function loadFormScoreChart(scores, labels) {
    const container = document.getElementById('formScoreChart');
    if (!container) return;
    
    container.innerHTML = '';
    
    scores.forEach((score, idx) => {
        const height = (score / 100) * 180; // Scale to chart height
        const bar = `
            <div class="flex-1 flex flex-col items-center">
                <div class="w-8 bg-gradient-to-t from-blue-500 to-blue-300 rounded-t-lg" 
                     style="height: ${height}px">
                </div>
                <span class="text-xs text-gray-500 mt-2">${labels[idx] || 'W' + (idx + 1)}</span>
                <span class="text-xs font-bold mt-1">${score}%</span>
            </div>
        `;
        container.innerHTML += bar;
    });
}

function loadWorkoutFrequencyChart(counts, dayLabels) {
    const container = document.getElementById('workoutFrequencyChart');
    if (!container) return;
    
    container.innerHTML = '';
    
    const maxCount = Math.max(...counts);
    
    counts.forEach((count, idx) => {
        const height = maxCount > 0 ? (count / maxCount) * 180 : 0;
        const bar = `
            <div class="flex-1 flex flex-col items-center">
                <div class="w-10 bg-gradient-to-t from-green-500 to-green-300 rounded-t-lg" 
                     style="height: ${height}px">
                </div>
                <span class="text-xs text-gray-500 mt-2">${dayLabels[idx] || ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'][idx]}</span>
                <span class="text-xs font-bold mt-1">${count}</span>
            </div>
        `;
        container.innerHTML += bar;
    });
}

function loadExerciseBreakdown(exerciseNames, exerciseCounts) {
    const container = document.getElementById('exerciseBreakdown');
    if (!container) return;
    
    container.innerHTML = '';
    
    const maxCount = Math.max(...exerciseCounts);
    
    exerciseNames.forEach((name, idx) => {
        const count = exerciseCounts[idx] || 0;
        const percentage = maxCount > 0 ? (count / maxCount) * 100 : 0;
        
        const breakdownCard = `
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="font-medium">${name}</span>
                    <span class="text-gray-600">${count} sessions</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-purple-500 h-2 rounded-full" style="width: ${percentage}%"></div>
                </div>
            </div>
        `;
        container.innerHTML += breakdownCard;
    });
}

// Fallback data if backend fails
function getFallbackProgressData() {
    return {
        form_trend: {
            weeks: ['W1', 'W2', 'W3', 'W4', 'W5', 'W6', 'W7', 'W8', 'W9', 'W10', 'W11', 'W12'],
            scores: [65, 70, 68, 75, 78, 82, 80, 85, 87, 89, 92, 90]
        },
        weekly_frequency: {
            days: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            counts: [3, 4, 2, 5, 4, 6, 5]
        },
        exercise_breakdown: {
            exercises: ['Squat', 'Push-up', 'Plank', 'Lunge', 'Sit-up'],
            counts: [15, 12, 8, 10, 6]
        }
    };
}

// Optional: Fetch fresh data from API (if you want real-time updates)
async function fetchProgressData() {
    try {
        const response = await fetch('../backend/api/progress_data.php?type=all');
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching progress data:', error);
        return getFallbackProgressData();
    }
}

// Add this to your existing initialization
document.addEventListener('DOMContentLoaded', function() {
    console.log('SpotBro App Initialized');
    
    // If on progress page, load the dashboard
    if (window.location.pathname.includes('progress.php') || 
        document.getElementById('formScoreChart')) {
        loadProgressDashboard();
    }
});