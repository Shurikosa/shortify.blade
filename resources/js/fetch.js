document.addEventListener('click', async function (e) {
    if(!e.target.matches('button')) return;
    e.preventDefault();

    const buttonId = e.target.id;
    const form = e.target.closest('form');
    const linkId = form?.getAttribute('data-link-id');

    const createLink = 'dashboard/links/store';
    const updateLink = `dashboard/links/update/${linkId}`;
    const deleteLink = `dashboard/links/destroy/${linkId}`;

    switch (buttonId) {
        case 'shortenButton':
            const url = document.getElementById('url').value;
            await sendAjaxRequest(createLink, 'POST', {url});
            break;
        case 'updateButton':
            await sendAjaxRequest(updateLink, 'POST', {linkId});
            break;
        case 'deleteButton':
            if(confirm('Are you sure you want to delete this link?')) {
                await sendAjaxRequest(deleteLink, 'DELETE');
            }
            break;

        default:
            console.warn('Unknown button clicked', buttonId);

    }
});

function updateMessageContainer(type, message) {
    const messageContainer = document.getElementById('message-container');
    let alertClass;
    console.log(type,message);
    if (type === 'success') {
        alertClass = 'bg-green-100 border border-green-400 text-green-700';
    } else {
        alertClass = 'bg-red-100 border border-red-400 text-red-700 ';
    }
    messageContainer.innerHTML = `
        <div class="${alertClass} px-4 py-3 rounded relative mb-4">
            <span class="block sm:inline">${message}</span>
        </div>
    `;
}

function updateValidUntilField(linkId, validUntil) {
    const validUntilCell = document.querySelector(
        `tr[data-link-id="${linkId}"] td[data-field="valid_until"]`);
    console.log(validUntilCell)
    if(validUntilCell){
        validUntilCell.textContent = validUntil;
    }else {
        console.warn('No valid until cell found for link', linkId);
    }
}



async function sendAjaxRequest(url, method, data = {}) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
            body: method !== 'GET' ? JSON.stringify(data) : null
        });

        const result = await response.json();
        console.log(result);
        console.log(result.message)
        console.log(data.linkId)
        console.log(response.ok)
        console.log(result.id)
        console.log(result.valid_until)



        if (response.ok && result.id === 'success') {
            if(result.valid_until) {
                updateValidUntilField(data.linkId, result.valid_until);
            }
            updateMessageContainer('success', result.message);
        } else if (response.ok && result.id === 'error') {
            updateMessageContainer('error', result.message);
        }
    }catch (error) {
        console.error();
        updateMessageContainer('error', 'An error occurred. Check the console for details.');
    }
}

