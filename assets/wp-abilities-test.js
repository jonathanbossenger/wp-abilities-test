( function( wp ) {
    // In your WordPress plugin or theme JavaScript

    const apiFetch = wp.apiFetch;

    const { registerAbility, executeAbility, getAbilities } = wp.abilities;

    apiFetch( { path: '/wp-abilities/v1/abilities' } ).then( ( abilities ) => {
        console.log( abilities );
    } );

    // Register a notification ability which sends an alert to the user
    registerAbility({
        name: 'test-abilities/alert-user',
        label: 'Alert User',
        description: 'Display an alert message to the user',
        category: 'test-abilities',
        input_schema: {
            type: 'object',
            properties: {
                message: { type: 'string' },
            },
            required: ['message']
        },
        callback: async ({ message }) => {
            // Show browser notification
            alert(message);
        },
        permissionCallback: () => {
            return !!wp.data.select('core').getCurrentUser();
        }
    });

    // Hook into the check-debug-status button, and execute the test-abilities/debug-status ability
    const button = document.getElementById('check-debug-status');
    if (button){
        const resultPre = document.getElementById('debug-status-result');
        button.addEventListener('click', async () => {
            const result = await executeAbility('test-abilities/debug-status');
            resultPre.textContent = JSON.stringify(result, null, 2);
        });
    }
    // Hook into the alert-user button, and execute the test-abilities/alert-user ability
    const alertButton = document.getElementById('alert-user');
    if (alertButton){
        const message = document.getElementById('alert-message');
        alertButton.addEventListener('click', async () => {
            const msg = message.value || 'Hello from test-abilities/alert-user ability!';
            await executeAbility('test-abilities/alert-user', { message: msg });
        })
    }
}( wp ) );



