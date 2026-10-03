(function () {
  const h = React.createElement;
  const API_URL = window.location.origin;
  const emptyForm = { product_name: '', description: '', price: '', quantity: '' };

  function App() {
    const [token, setToken] = React.useState(() => localStorage.getItem('lab6_access_token') || '');
    const [refresh, setRefresh] = React.useState(() => localStorage.getItem('lab6_refresh_token') || '');
    const [username, setUsername] = React.useState('');
    const [password, setPassword] = React.useState('');
    const [products, setProducts] = React.useState([]);
    const [form, setForm] = React.useState(emptyForm);
    const [editing, setEditing] = React.useState(null);
    const [error, setError] = React.useState('');
    const [busy, setBusy] = React.useState(false);

    async function request(path, options) {
      const response = await fetch(API_URL + path, Object.assign({
        headers: Object.assign({ 'Content-Type': 'application/json' }, token ? { Authorization: 'Bearer ' + token } : {})
      }, options || {}));
      const body = await response.json().catch(() => ({}));
      if (response.status === 401 && refresh && !path.includes('/auth/')) {
        const refreshed = await fetch(API_URL + '/api/auth/refresh', {
          method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ refresh_token: refresh })
        });
        const refreshedBody = await refreshed.json().catch(() => ({}));
        if (refreshed.ok && refreshedBody.tokens) {
          const next = refreshedBody.tokens;
          setToken(next.access_token); setRefresh(next.refresh_token);
          localStorage.setItem('lab6_access_token', next.access_token);
          localStorage.setItem('lab6_refresh_token', next.refresh_token);
          return request(path, Object.assign({}, options, { headers: Object.assign({}, options && options.headers, { Authorization: 'Bearer ' + next.access_token }) }));
        }
      }
      if (!response.ok) throw new Error(body.error || 'Request failed (' + response.status + ').');
      return body;
    }

    async function loadProducts() {
      try { const result = await request('/api/products'); setProducts(result.data || []); setError(''); }
      catch (e) { setError(e.message); if (/unauthorized/i.test(e.message)) signOut(false); }
    }

    React.useEffect(() => { if (token) loadProducts(); }, [token]);

    async function signIn(event) {
      event.preventDefault(); setBusy(true); setError('');
      try {
        const result = await request('/api/auth/login', { method: 'POST', body: JSON.stringify({ username, password }) });
        setToken(result.tokens.access_token); setRefresh(result.tokens.refresh_token);
        localStorage.setItem('lab6_access_token', result.tokens.access_token);
        localStorage.setItem('lab6_refresh_token', result.tokens.refresh_token);
        setPassword('');
      } catch (e) { setError(e.message); }
      finally { setBusy(false); }
    }

    async function signOut(callApi) {
      if (callApi !== false && token) {
        try { await request('/api/auth/logout', { method: 'POST', body: JSON.stringify({ refresh_token: refresh }) }); } catch (_) {}
      }
      localStorage.removeItem('lab6_access_token'); localStorage.removeItem('lab6_refresh_token');
      setToken(''); setRefresh(''); setProducts([]); setEditing(null); setForm(emptyForm);
    }

    function startEdit(product) {
      setEditing(product.id);
      setForm({ product_name: product.product_name, description: product.description || '', price: product.price, quantity: product.quantity });
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    async function saveProduct(event) {
      event.preventDefault(); setBusy(true); setError('');
      try {
        const path = editing ? '/api/products/' + editing : '/api/products';
        await request(path, { method: editing ? 'PUT' : 'POST', body: JSON.stringify(Object.assign({}, form, { price: Number(form.price), quantity: Number(form.quantity) })) });
        setForm(emptyForm); setEditing(null); await loadProducts();
      } catch (e) { setError(e.message); }
      finally { setBusy(false); }
    }

    async function removeProduct(id) {
      if (!window.confirm('Delete this product? This cannot be undone.')) return;
      try { await request('/api/products/' + id, { method: 'DELETE' }); await loadProducts(); }
      catch (e) { setError(e.message); }
    }

    function field(label, key, type, extra) {
      return h('div', { className: 'field', key }, h('label', { htmlFor: key }, label),
        type === 'textarea'
          ? h('textarea', Object.assign({ id: key, className: 'input', value: form[key], onChange: e => setForm(Object.assign({}, form, { [key]: e.target.value })) }, extra || {}))
          : h('input', Object.assign({ id: key, className: 'input', type: type || 'text', value: form[key], onChange: e => setForm(Object.assign({}, form, { [key]: e.target.value })) }, extra || {})));
    }

    const totalUnits = products.reduce((sum, product) => sum + Number(product.quantity || 0), 0);
    const stockValue = products.reduce((sum, product) => sum + Number(product.price || 0) * Number(product.quantity || 0), 0);

    if (!token) return h('main', { className: 'login card' },
      h('div', { className: 'brand' }, h('div', { className: 'mark' }, 'F'), h('div', null, h('div', { className: 'eyebrow' }, 'FORGE  /  INVENTORY'), h('h1', null, 'Product Manager'))),
      h('div', { className: 'login-intro' }, h('div', { className: 'eyebrow' }, 'LABORATORY EXERCISE NO. 6'), h('h2', null, 'A clearer view of your inventory.'), h('p', { className: 'muted' }, 'Sign in to manage products, stock, and pricing from one place.')),
      error && h('p', { className: 'alert' }, error),
      h('form', { onSubmit: signIn },
        h('div', { className: 'field' }, h('label', { htmlFor: 'username' }, 'Username'), h('input', { id: 'username', className: 'input', autoComplete: 'username', required: true, value: username, onChange: e => setUsername(e.target.value), placeholder: 'Enter your username' })),
        h('div', { className: 'field' }, h('label', { htmlFor: 'password' }, 'Password'), h('input', { id: 'password', className: 'input', type: 'password', autoComplete: 'current-password', required: true, value: password, onChange: e => setPassword(e.target.value), placeholder: 'Enter your password' })),
        h('button', { className: 'btn login-submit', disabled: busy }, busy ? 'Signing in…' : 'Sign in to workspace')),
      h('div', { className: 'login-foot' }, h('span', { className: 'status-dot' }), 'SECURE ACCESS  ·  LAVALUST API'));

    return h('main', { className: 'shell' },
      h('header', { className: 'top' }, h('div', { className: 'brand' }, h('div', { className: 'mark' }, 'F'), h('div', null, h('div', { className: 'eyebrow' }, 'FORGE  /  INVENTORY'), h('h1', null, 'Product Manager'))), h('div', { className: 'header-actions' }, h('span', { className: 'session-label' }, h('span', { className: 'status-dot' }), 'WORKSPACE ACTIVE'), h('button', { className: 'btn light logout', onClick: () => signOut(true) }, 'Log out'))),
      h('section', { className: 'toolbar' }, h('div', { className: 'heading' }, h('div', { className: 'eyebrow' }, 'LABORATORY EXERCISE NO. 6  /  OVERVIEW'), h('h2', null, 'Inventory, in focus.'), h('p', null, 'Manage your catalog and keep every detail in order.'))),
      error && h('p', { className: 'alert' }, error),
      h('section', { className: 'stats' },
        h('article', { className: 'stat-card card' }, h('span', { className: 'stat-label' }, 'TOTAL PRODUCTS'), h('strong', null, String(products.length).padStart(2, '0')), h('span', { className: 'stat-note' }, 'Active catalog items')),
        h('article', { className: 'stat-card card' }, h('span', { className: 'stat-label' }, 'UNITS IN STOCK'), h('strong', null, totalUnits.toLocaleString()), h('span', { className: 'stat-note' }, 'Across all products')),
        h('article', { className: 'stat-card card stat-value' }, h('span', { className: 'stat-label' }, 'STOCK VALUE'), h('strong', null, '₱' + stockValue.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })), h('span', { className: 'stat-note' }, 'Based on current quantities'))),
      h('div', { className: 'layout' },
        h('section', { className: 'card table-card' }, h('div', { className: 'table-heading' }, h('div', null, h('div', { className: 'eyebrow' }, 'CATALOG'), h('h3', null, 'Product list')), h('span', { className: 'count' }, products.length + (products.length === 1 ? ' ITEM' : ' ITEMS'))), products.length ? h('div', { className: 'table-scroll' }, h('table', null,
          h('thead', null, h('tr', null, ['Product', 'Price', 'Quantity', 'Actions'].map(x => h('th', { key: x }, x)))),
          h('tbody', null, products.map(p => h('tr', { key: p.id },
            h('td', null, h('strong', null, p.product_name), h('div', { className: 'desc' }, p.description || 'No description')),
            h('td', null, '₱' + Number(p.price).toLocaleString('en-PH', { minimumFractionDigits: 2 })), h('td', null, p.quantity),
            h('td', null, h('div', { className: 'actions' }, h('button', { className: 'btn light small', onClick: () => startEdit(p) }, 'Edit'), h('button', { className: 'btn danger small', onClick: () => removeProduct(p.id) }, 'Delete')))))))) : h('div', { className: 'empty' }, h('div', { className: 'empty-mark' }, 'F'), h('strong', null, 'Your catalog starts here'), h('p', null, 'Add a product to begin building your inventory.'))),
        h('form', { className: 'card form-card', onSubmit: saveProduct }, h('div', { className: 'form-kicker eyebrow' }, editing ? 'UPDATE DETAILS' : 'NEW CATALOG ITEM'), h('h3', null, editing ? 'Edit product' : 'Add a product'), h('p', { className: 'form-copy' }, editing ? 'Make changes to this catalog entry.' : 'Enter the details for your next item.'),
          field('Product name', 'product_name', 'text', { maxLength: 100, required: true, placeholder: 'e.g. Leather weekender' }),
          field('Description', 'description', 'textarea', { placeholder: 'Materials, details, and notes' }),
          h('div', { className: 'form-row' }, field('Price (PHP)', 'price', 'number', { min: '0', step: '0.01', required: true, placeholder: '0.00' }), field('Quantity', 'quantity', 'number', { min: '0', step: '1', required: true, placeholder: '0' })),
          h('div', { className: 'form-actions' }, h('button', { className: 'btn', disabled: busy }, busy ? 'Saving…' : editing ? 'Save changes' : 'Add to catalog'),
            editing && h('button', { type: 'button', className: 'btn light', onClick: () => { setEditing(null); setForm(emptyForm); } }, 'Cancel'))),
      h('p', { className: 'footer' }, 'SECURE INVENTORY WORKSPACE  ·  PRODUCT DATA PROTECTED BY LAVALUST API'));
  }

  ReactDOM.createRoot(document.getElementById('root')).render(h(App));
})();
