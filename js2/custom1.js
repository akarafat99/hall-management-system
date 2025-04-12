// Select all navigation links in the sidebar and offcanvas
const navLinks = document.querySelectorAll('.sidebar a, .offcanvas a');

navLinks.forEach(link => {
    link.addEventListener('mouseover', () => {
        // Animate scaling up on hover
        link.animate([
            { transform: 'scale(1)' },
            { transform: 'scale(1.03)' }
        ], {
            duration: 200,
            fill: 'forwards'
        });
    });

    link.addEventListener('mouseout', () => {
        // Animate scaling back down when not hovered
        link.animate([
            { transform: 'scale(1.03)' },
            { transform: 'scale(1)' }
        ], {
            duration: 200,
            fill: 'forwards'
        });
    });
});
