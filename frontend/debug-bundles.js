console.log('Debug bundles script loaded');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, starting bundles loader');
    
    const container = document.getElementById('tours-dynamic');
    if (!container) {
        console.error('Container tours-dynamic not found');
        return;
    }
    
    console.log('Container found:', container);
    
    // Простая загрузка данных
    fetch('http://94.228.124.202:8080/api/frontend/bundles')
        .then(response => {
            console.log('Response:', response);
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            
            if (data.success && data.data.length > 0) {
                container.innerHTML = '';
                data.data.forEach((bundle, index) => {
                    const card = document.createElement('div');
                    card.className = 'tour';
                    card.innerHTML = '<h2>' + bundle.name + '</h2><p>' + bundle.description + '</p>';
                    container.appendChild(card);
                });
                console.log('Bundles rendered successfully');
            } else {
                container.innerHTML = '<div>No bundles found</div>';
                console.log('No bundles found');
            }
        })
        .catch(error => {
            console.error('Error loading bundles:', error);
            container.innerHTML = '<div style="color: red;">Error loading bundles: ' + error.message + '</div>';
        });
});
