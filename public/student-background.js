(() => {
  const canvas = document.getElementById('network-bg');
  if (!canvas) return;
  const ctx = canvas.getContext('2d');
  const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  let width = 0, height = 0, particles = [], pointer = { x: -1000, y: -1000 };

  function resize() {
    const ratio = Math.min(window.devicePixelRatio || 1, 2);
    width = window.innerWidth; height = window.innerHeight;
    canvas.width = width * ratio; canvas.height = height * ratio;
    canvas.style.width = width + 'px'; canvas.style.height = height + 'px';
    ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
    const count = reduceMotion ? 18 : Math.min(58, Math.max(28, Math.floor(width * height / 19000)));
    particles = Array.from({ length: count }, () => ({
      x: Math.random() * width, y: Math.random() * height,
      vx: (Math.random() - .5) * (reduceMotion ? .08 : .62),
      vy: (Math.random() - .5) * (reduceMotion ? .08 : .62),
      radius: Math.random() * 2 + 1
    }));
  }

  function draw(time = 0) {
    ctx.clearRect(0, 0, width, height); const waveTime=time/420; for(let b=0;b<3;b++){ctx.save();ctx.globalCompositeOperation='screen';ctx.filter='blur(24px)';ctx.strokeStyle=['rgba(26,224,215,.20)','rgba(53,141,255,.16)','rgba(92,250,182,.14)'][b];ctx.lineWidth=70;ctx.beginPath();for(let x=-30;x<width+35;x+=24){const y=height*(.2+b*.27)+Math.sin(x/115+waveTime*(.8+b*.16)+b)*40+Math.sin(x/50-waveTime*.6)*12;x===-30?ctx.moveTo(x,y):ctx.lineTo(x,y)}ctx.stroke();ctx.restore()}
    const wave = ctx.createRadialGradient(width * .2, height * .1, 0, width * .2, height * .1, width * .75);
    wave.addColorStop(0, 'rgba(30, 133, 160, .34)'); wave.addColorStop(.55, 'rgba(16, 72, 105, .08)'); wave.addColorStop(1, 'rgba(3, 14, 28, 0)');
    ctx.fillStyle = wave; ctx.fillRect(0, 0, width, height);
    const glow = ctx.createRadialGradient(width * .85, height * .8, 0, width * .85, height * .8, width * .55);
    glow.addColorStop(0, 'rgba(32, 190, 164, .25)'); glow.addColorStop(1, 'rgba(3, 14, 28, 0)');
    ctx.fillStyle = glow; ctx.fillRect(0, 0, width, height);

    particles.forEach(p => {
      if (!reduceMotion) { p.x += p.vx; p.y += p.vy; }
      if (p.x < -10) p.x = width + 10; if (p.x > width + 10) p.x = -10;
      if (p.y < -10) p.y = height + 10; if (p.y > height + 10) p.y = -10;
      const dx = pointer.x - p.x, dy = pointer.y - p.y, distance = Math.hypot(dx, dy);
      if (!reduceMotion && distance < 190 && distance > 0) { p.x -= dx / distance * .46; p.y -= dy / distance * .46; }
      ctx.beginPath(); ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
      ctx.fillStyle = 'rgba(104, 232, 211, .82)'; ctx.shadowBlur = 12; ctx.shadowColor = '#67dec7'; ctx.fill(); ctx.shadowBlur = 0;
    });

    for (let i = 0; i < particles.length; i++) for (let j = i + 1; j < particles.length; j++) {
      const a = particles[i], b = particles[j], distance = Math.hypot(a.x - b.x, a.y - b.y);
      if (distance < 150) { ctx.beginPath(); ctx.moveTo(a.x, a.y); ctx.lineTo(b.x, b.y); ctx.strokeStyle = `rgba(103, 222, 199, ${(.18 * (1 - distance / 125)).toFixed(3)})`; ctx.lineWidth = 1; ctx.stroke(); }
    }
    if (!reduceMotion) requestAnimationFrame(draw);
  }

  window.addEventListener('resize', resize);
  window.addEventListener('pointermove', event => { pointer.x = event.clientX; pointer.y = event.clientY; });
  window.addEventListener('pointerleave', () => { pointer.x = -1000; pointer.y = -1000; });
  const textWalker=document.createTreeWalker(document.body,NodeFilter.SHOW_TEXT,{acceptNode:n=>(!n.nodeValue.trim()||n.parentElement.closest('script,style,code,.letter-glow'))?NodeFilter.FILTER_REJECT:NodeFilter.FILTER_ACCEPT}),textNodes=[];while(textWalker.nextNode())textNodes.push(textWalker.currentNode);textNodes.forEach(n=>{const f=document.createDocumentFragment();[...n.nodeValue].forEach(c=>{if(/\s/.test(c))return f.append(document.createTextNode(c));const s=document.createElement('span');s.className='letter-glow';s.textContent=c;f.append(s)});n.parentNode.replaceChild(f,n)});document.addEventListener('click',e=>{const l=e.target.closest('.letter-glow');if(l){l.classList.remove('letter-clicked');void l.offsetWidth;l.classList.add('letter-clicked')}const target=e.target.closest('.item,h1');if(!target)return;document.querySelectorAll('.detail-active').forEach(node=>node.classList.remove('detail-active'));target.classList.add('detail-active')}); resize(); draw();
})();
