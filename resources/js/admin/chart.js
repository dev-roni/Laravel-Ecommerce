// js/chart.js
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('salesChart');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    
    function resizeCanvas() {
        canvas.width = canvas.parentElement.offsetWidth;
        canvas.height = canvas.parentElement.offsetHeight;
        drawChart();
    }
    
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    function drawChart() {
        const w = canvas.width;
        const h = canvas.height;
        const padding = 40;
        ctx.clearRect(0, 0, w, h);

        const data = [15000, 22000, 18000, 28000, 24000, 35000, 25400];
        const labels = ['শনি', 'রবি', 'সোম', 'মঙ্গল', 'বুধ', 'বৃহঃ', 'শুক্র'];
        const maxVal = Math.max(...data) * 1.2;
        const stepX = (w - padding * 2) / (data.length - 1);
        
        // Grid
        ctx.strokeStyle = '#e0e0e0';
        ctx.lineWidth = 1;
        ctx.beginPath();
        for(let i=0; i<=5; i++) {
            const y = h - padding - (i * (h - 2*padding) / 5);
            ctx.moveTo(padding, y);
            ctx.lineTo(w - padding, y);
            ctx.fillStyle = '#999';
            ctx.font = '10px Hind Siliguri';
            ctx.textAlign = 'right';
            ctx.fillText(Math.round(maxVal/5 * i), padding - 10, y + 3);
        }
        ctx.stroke();

        // Line
        ctx.beginPath();
        ctx.strokeStyle = '#3498db';
        ctx.lineWidth = 3;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';
        const points = [];

        data.forEach((val, index) => {
            const x = padding + index * stepX;
            const y = h - padding - (val / maxVal) * (h - 2 * padding);
            points.push({x, y});
            if(index === 0) ctx.moveTo(x, y);
            else ctx.lineTo(x, y);
            ctx.fillStyle = '#666';
            ctx.font = '12px Hind Siliguri';
            ctx.textAlign = 'center';
            ctx.fillText(labels[index], x, h - 10);
        });
        ctx.stroke();

        // Gradient Fill
        ctx.beginPath();
        ctx.moveTo(points[0].x, h - padding);
        points.forEach(p => ctx.lineTo(p.x, p.y));
        ctx.lineTo(points[points.length-1].x, h - padding);
        ctx.closePath();
        const gradient = ctx.createLinearGradient(0, 0, 0, h);
        gradient.addColorStop(0, 'rgba(52, 152, 219, 0.2)');
        gradient.addColorStop(1, 'rgba(52, 152, 219, 0)');
        ctx.fillStyle = gradient;
        ctx.fill();

        // Dots
        points.forEach((p) => {
            ctx.beginPath();
            ctx.fillStyle = '#fff';
            ctx.strokeStyle = '#3498db';
            ctx.lineWidth = 2;
            ctx.arc(p.x, p.y, 5, 0, Math.PI * 2);
            ctx.fill();
            ctx.stroke();
        });
    }
});