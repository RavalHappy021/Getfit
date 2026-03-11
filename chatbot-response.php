<?php
header("Content-Type: text/plain");

if (isset($_POST['message'])) {
    $userMessage = trim($_POST['message']);

    if (stripos($userMessage, 'hello') !== false) {
        echo "Hi there! How can I assist you with fitness or nutrition today?";
    } elseif (stripos($userMessage, 'diet') !== false) {
        echo "A balanced diet is key! Are you looking for weight loss or muscle gain tips?";
    } elseif (stripos($userMessage, 'workout') !== false) {
        echo "I can suggest a workout plan! Are you a beginner, intermediate, or advanced?";
    } elseif (stripos($userMessage, 'calorie') !== false || stripos($userMessage, 'calories') !== false) {
        echo "You can calculate your daily calorie needs using the TDEE formula. Want help with that?";
    } elseif (stripos($userMessage, 'bmi') !== false) {
        echo "BMI (Body Mass Index) helps estimate body fat. Want a quick formula?";
    } elseif (stripos($userMessage, 'water') !== false) {
        echo "Drinking 2–3 liters of water per day is great! Need help setting a goal?";
    } elseif (stripos($userMessage, 'motivation') !== false) {
        echo "You're stronger than you think! Keep going — every rep counts!";
    } elseif (stripos($userMessage, 'supplement') !== false) {
        echo "Whey protein, creatine, and multivitamins are common. Need specific suggestions?";
    } elseif (stripos($userMessage, 'sleep') !== false) {
        echo "Sleep is crucial! Aim for 7–9 hours to recover and grow.";
    } elseif (stripos($userMessage, 'fat loss') !== false || stripos($userMessage, 'lose fat') !== false) {
        echo "Focus on a calorie deficit, cardio, and clean eating to burn fat effectively.";
    } elseif (stripos($userMessage, 'build muscle') !== false || stripos($userMessage, 'gain muscle') !== false) {
        echo "Muscle building needs progressive overload and a protein-rich diet. Consistency is key!";
    } elseif (stripos($userMessage, 'yoga') !== false) {
        echo "Yoga improves flexibility and reduces stress. A great low-impact fitness option!";
    } elseif (stripos($userMessage, 'cardio') !== false) {
        echo "Cardio boosts heart health and aids fat loss. Try brisk walking, cycling, or HIIT!";
    } elseif (stripos($userMessage, 'protein') !== false) {
        echo "Protein helps with muscle repair. Aim for 1.6–2.2g per kg of body weight.";
    } elseif (stripos($userMessage, 'stress') !== false) {
        echo "Try deep breathing, exercise, or meditation to manage stress naturally.";
    } elseif (stripos($userMessage, 'rest day') !== false || stripos($userMessage, 'rest days') !== false) {
        echo "Rest is essential! Muscles grow when you recover. Plan at least 1–2 rest days per week.";
    } elseif (stripos($userMessage, 'stretch') !== false || stripos($userMessage, 'stretching') !== false) {
        echo "Stretching improves mobility and prevents injury. Always warm up and cool down!";
    } elseif (stripos($userMessage, 'meal timing') !== false || stripos($userMessage, 'when to eat') !== false) {
        echo "Meal timing can help performance. Try eating carbs before workouts and protein after.";
    } else {
        echo "I'm still learning! Try asking about diet, workouts, sleep, or motivation.";
    }
} else {
    echo "No message received.";
}
?>
