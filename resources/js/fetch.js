document.addEventListener('click', async function (e) {
    if(!e.target.matches('button')) return;
    e.preventDefault();

    const buttonId = e.target.id;
    const form = e.target.closest('form');
    const linkId = form?.getAttribute('data-link-id');

    const createLink = 'dashboard/links/store';
    const updateLink = 'dashboard/links/update/${linkId}';
    const deleteLink = 'dashboard/links/destroy/${linkId}';

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    switch (buttonId) {
        case 'shortenButton':
            const url = document.getElementById('url').value;
            await sendAjaxRequest(createLink, 'POST', {url}, csrfToken);
            break;
        case 'updateButton':
            await sendAjaxRequest(updateLink, 'POST', csrfToken);
            break;
        case 'deleteButton':
            if(confirm('Are you sure you want to delete this link?')) {
                await sendAjaxRequest(deleteLink, 'DELETE', csrfToken);
            }
            break;

        default:
            console.warn('Unknown button clicked', buttonId);

    }
});

function updateMessageContainer(type, message) {
    const messageContainer = document.getElementById('message-container');
    let alertClass;
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



async function sendAjaxRequest(url, method, data, csrfToken) {
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

        if (response.ok) {
            updateMessageContainer('success', result.message);
        } else {
            updateMessageContainer('error', result.message);
        }
    }catch (error) {
        console.error();
        updateMessageContainer('error', 'An error occurred. Check the console for details.');
    }
}

