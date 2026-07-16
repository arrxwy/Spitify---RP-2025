const hoverDivs = document.querySelectorAll('.karta-link');
const targetDivs = document.querySelectorAll('.artist_title');

// Add initial styles to all artist titles
targetDivs.forEach(div => {
    div.style.opacity = '0';
    div.style.fontSize = '1em';
    div.style.transition = 'opacity 0.5s ease, font-size 0.5s ease';
    div.style.display = 'none';
});

hoverDivs.forEach((hoverDiv, index) => {
    let timeoutId = null;

    hoverDiv.addEventListener('mouseenter', () => {
        if (targetDivs[index]) {
            // Clear any existing timeout
            if (timeoutId) {
                clearTimeout(timeoutId);
                timeoutId = null;
            }
            
            targetDivs[index].style.display = 'block';
            // Use requestAnimationFrame to ensure smooth transition
            requestAnimationFrame(() => {
                targetDivs[index].style.opacity = '1';
                targetDivs[index].style.fontSize = '1.7em';
            });
        }
    });

    hoverDiv.addEventListener('mouseleave', () => {
        if (targetDivs[index]) {
            targetDivs[index].style.opacity = '0';
            targetDivs[index].style.fontSize = '1em';  // Fixed typo: was '1.5m'
            
            // Set timeout to hide element after transition completes
            timeoutId = setTimeout(() => {
                if (targetDivs[index].style.opacity === '0') {  // Only hide if still faded out
                    targetDivs[index].style.display = 'none';
                }
                timeoutId = null;
            }, 500);
        }
    });
});