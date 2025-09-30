( function( wp ) {
    // In your WordPress plugin or theme JavaScript
    const { registerAbility, executeAbility } = wp.abilities;

    // Register a notification ability which sends an alert to the user
    registerAbility({
        name: 'my-plugin/alert-user',
        label: 'Alert User',
        description: 'Display an alert message to the user',
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

    // Hook into the check-debug-status button, and execute the my-plugin/debug-status ability
    // Update the debug-status-result pre with the result
    const button = document.getElementById('check-debug-status');
    const resultPre = document.getElementById('debug-status-result');
    button.addEventListener('click', async () => {
        const result = await executeAbility('my-plugin/debug-status');
        resultPre.textContent = JSON.stringify(result, null, 2);
    });

    // Hook into the alert-user button, and execute the my-plugin/alert-user ability
    const alertButton = document.getElementById('alert-user');
    const message = document.getElementById('alert-message');
    alertButton.addEventListener('click', async () => {
        const msg = message.value || 'Hello from my-plugin/alert-user ability!';
        await executeAbility('my-plugin/alert-user', { message: msg });
    })

}( wp ) );



