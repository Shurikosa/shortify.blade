document.getElementById('updateButton').addEventListener('click', async function (e) {
    e.preventDefault(); // Зупиняємо стандартну поведінку форми.

   const linkId = document.getElementById('updateForm').getAttribute('data-link-id');
   const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    try {
        const response = await fetch(`dashboard/links/${linkId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
        });

        const result = await response.json();

        if (response.ok) {
            updateMessageContainer('success', result.message);
        } else {
            updateMessageContainer('error', result.message);
        }
    } catch (e) {
        console.error();
        updateMessageContainer('error', 'An error occurred. Check the console for details.');
    }

});


