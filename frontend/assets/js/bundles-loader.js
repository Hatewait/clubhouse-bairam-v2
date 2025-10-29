console.log('BundlesLoader v6 - Debug images version loaded');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, starting bundles loader');
    
    const container = document.getElementById('tours-dynamic');
    if (!container) {
        console.error('Container tours-dynamic not found');
        return;
    }
    
    console.log('Container found:', container);
    
    // Загрузка данных
    fetch('http://94.228.124.202:8080/api/frontend/bundles')
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('HTTP error! status: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            
            if (data.success && data.data.length > 0) {
                // Очищаем контейнер
                container.innerHTML = '';
                console.log('Container cleared');
                
                data.data.forEach((bundle, index) => {
                    console.log('Creating card for:', bundle.name);
                    console.log('Bundle gallery:', bundle.gallery);
                    
                    // Получаем изображение из галереи
                    let imageUrl = '';
                    if (bundle.gallery && bundle.gallery.length > 0) {
                        imageUrl = bundle.gallery[0];
                        console.log('Raw image path:', imageUrl);
                        
                        // Формируем полный URL
                        if (!imageUrl.startsWith('http')) {
                            imageUrl = 'http://94.228.124.202:8080/storage/' + imageUrl;
                        }
                        console.log('Full image URL:', imageUrl);
                    }
                    
                    // Создаем карточку с правильной структурой
                    const card = document.createElement('a');
                    card.className = 'tour';
                    card.href = bundle.name.toLowerCase().includes('аренда') ? 'arenda.php' : 'avtorskie-tury.php';
                    
                    // Создаем HTML структуру карточки
                    card.innerHTML = `
                        <div class="tour__bg" style="background-image: url('${imageUrl}');"></div>
                        <div class="tour__top">
                            <h2 class="subtitle-xl-accent color-white space-sm">${bundle.name}</h2>
                            <p class="text-md-regular color-white">${bundle.subtitle || 'Описание формата отдыха'}</p>
                        </div>
                        <div class="tour__bottom">
                            <p class="tour__text color-white">${bundle.description || 'Описание формата отдыха будет добавлено администратором.'}</p>
                            <button class="button-filled color-white buttons-lg-medium" data-graph-path="consult" aria-label="Подробнее">Подробнее</button>
                        </div>
                    `;
                    
                    container.appendChild(card);
                    console.log('Card added to container:', card);
                });
                
                console.log('All cards created. Container children count:', container.children.length);
                
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
