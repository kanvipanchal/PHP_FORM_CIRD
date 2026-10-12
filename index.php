<?php /* index.php - the page. Data is loaded by assets/app.js from api.php */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Book</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main>
    <h1>Contact Book</h1>

    <section class="panel">
        <h2 id="form-title">Add a contact</h2>
        <form id="contact-form" novalidate>
            <input type="hidden" id="contact-id">

            <label for="name">Name</label>
            <input type="text" id="name" required>

            <label for="email">Email</label>
            <input type="email" id="email" required>

            <label for="phone">Phone <span class="hint">(optional)</span></label>
            <input type="tel" id="phone">

            <p id="message" role="status" aria-live="polite"></p>

            <div class="actions">
                <button type="submit" id="save-btn">Add contact</button>
                <button type="button" id="cancel-btn" class="secondary" hidden>Cancel edit</button>
            </div>
        </form>
    </section>

    <section class="panel">
        <h2>All contacts</h2>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Name</th><th>Email</th><th>Phone</th><th></th></tr>
                </thead>
                <tbody id="contact-list"></tbody>
            </table>
        </div>
        <p id="empty" hidden>No contacts yet. Add your first one above.</p>
    </section>
</main>
<script src="assets/app.js"></script>
</body>
</html>
