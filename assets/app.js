// app.js - talks to api.php using fetch()
const API = 'api.php';

const form      = document.getElementById('contact-form');
const idInput   = document.getElementById('contact-id');
const nameInput = document.getElementById('name');
const emailInput = document.getElementById('email');
const phoneInput = document.getElementById('phone');
const message   = document.getElementById('message');
const saveBtn   = document.getElementById('save-btn');
const cancelBtn = document.getElementById('cancel-btn');
const formTitle = document.getElementById('form-title');
const list      = document.getElementById('contact-list');
const empty     = document.getElementById('empty');

let contacts = [];

// small helper for every API call
async function request(url, method = 'GET', body = null) {
    const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json' },
        body: body ? JSON.stringify(body) : null
    });
    const data = await res.json();
    if (!res.ok) throw new Error(data.error || 'Something went wrong.');
    return data;
}

function showMessage(text, type) {
    message.textContent = text;
    message.className = type || '';
}

// READ
async function loadContacts() {
    contacts = await request(API);
    render();
}

function render() {
    list.innerHTML = '';
    empty.hidden = contacts.length > 0;

    contacts.forEach(c => {
        const tr = document.createElement('tr');
        [c.name, c.email, c.phone || '-'].forEach(text => {
            const td = document.createElement('td');
            td.textContent = text;           // textContent keeps user input safe from XSS
            tr.appendChild(td);
        });

        const actions = document.createElement('td');
        actions.className = 'row-actions';
        actions.append(
            makeButton('Edit', 'secondary small', () => startEdit(c)),
            makeButton('Delete', 'danger small', () => removeContact(c))
        );
        tr.appendChild(actions);
        list.appendChild(tr);
    });
}

function makeButton(label, classes, onClick) {
    const b = document.createElement('button');
    b.type = 'button';
    b.className = classes;
    b.textContent = label;
    b.addEventListener('click', onClick);
    return b;
}

// CREATE + UPDATE
form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const payload = {
        name: nameInput.value,
        email: emailInput.value,
        phone: phoneInput.value
    };
    const id = idInput.value;

    try {
        const result = id
            ? await request(`${API}?id=${id}`, 'PUT', payload)
            : await request(API, 'POST', payload);
        resetForm();
        showMessage(result.message, 'ok');
        await loadContacts();
    } catch (err) {
        showMessage(err.message, 'error');
    }
});

function startEdit(c) {
    idInput.value = c.id;
    nameInput.value = c.name;
    emailInput.value = c.email;
    phoneInput.value = c.phone || '';
    formTitle.textContent = 'Edit contact';
    saveBtn.textContent = 'Save changes';
    cancelBtn.hidden = false;
    showMessage('');
    nameInput.focus();
}

function resetForm() {
    form.reset();
    idInput.value = '';
    formTitle.textContent = 'Add a contact';
    saveBtn.textContent = 'Add contact';
    cancelBtn.hidden = true;
}

cancelBtn.addEventListener('click', () => { resetForm(); showMessage(''); });

// DELETE
async function removeContact(c) {
    if (!confirm(`Delete ${c.name}?`)) return;
    try {
        const result = await request(`${API}?id=${c.id}`, 'DELETE');
        showMessage(result.message, 'ok');
        await loadContacts();
    } catch (err) {
        showMessage(err.message, 'error');
    }
}

loadContacts().catch(err => showMessage(err.message, 'error'));
