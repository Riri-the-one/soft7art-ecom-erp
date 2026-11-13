const {chromium}=require('../.audit/node_modules/playwright');
const fs=require('fs');const path=require('path');const {spawn,spawnSync}=require('child_process');
if(fs.existsSync('bootstrap/cache/config.php'))throw Error('Clear cached configuration before isolated browser tests');
const env={...process.env,APP_NAME:'SOFT7ART ERP',APP_ENV:'local',APP_DEBUG:'false',APP_KEY:'base64:'+require('crypto').randomBytes(32).toString('base64'),APP_URL:'http://127.0.0.1:8127',DB_CONNECTION:'sqlite',DB_DATABASE:path.resolve('.audit/demo-'+Date.now()+'.sqlite'),DB_URL:'',DEMO_SEEDING:'true',SESSION_DRIVER:'file',CACHE_STORE:'array',MAIL_MAILER:'array',QUEUE_CONNECTION:'sync'};
if(fs.existsSync(env.DB_DATABASE))throw Error('Refusing to reuse database');fs.writeFileSync(env.DB_DATABASE,'');
const seed=spawnSync('php',['artisan','migrate','--seed','--force'],{env,encoding:'utf8'});if(seed.status)throw Error(seed.stdout+seed.stderr);
const password=seed.stdout.match(/Isolated demo password \(all three accounts\): (\w+)/)?.[1];if(!password)throw Error('No demo password');
const server=spawn('php',['-S','127.0.0.1:8127','-t','public'],{env,stdio:'ignore'});
(async()=>{let browser;try{browser=await chromium.launch({channel:'msedge',headless:true});const page=await browser.newPage({viewport:{width:1440,height:1000}});const errors=[];page.on('pageerror',e=>errors.push(e.message));
for(let n=0;n<30;n++){try{await page.goto(env.APP_URL+'/login');break;}catch{await new Promise(r=>setTimeout(r,200));}}
await page.locator('[name=email]').fill('admin@example.test');await page.locator('[name=password]').fill(password);await Promise.all([page.waitForURL('**/dashboard'),page.locator('button[type=submit]').click()]);
fs.mkdirSync('docs/screenshots',{recursive:true});let results=[];
for(const [route,name] of [['dashboard','dashboard'],['products','products'],['orders','orders'],['customers','crm'],['statistics','statistics'],['orders/kanban','kanban']]){const response=await page.goto(env.APP_URL+'/'+route);await page.waitForTimeout(700);results.push({route,status:response.status()});await page.screenshot({path:'docs/screenshots/'+name+'.png',fullPage:true});}
for(const width of [320,390,768,1440]){await page.setViewportSize({width,height:900});for(const route of ['dashboard','products','orders','customers','statistics']){await page.goto(env.APP_URL+'/'+route);await page.waitForTimeout(250);const overflow=await page.evaluate(()=>document.documentElement.scrollWidth>innerWidth);results.push({route,width,overflow});}if(width===390){await page.goto(env.APP_URL+'/dashboard');await page.waitForTimeout(700);await page.screenshot({path:'docs/screenshots/mobile.png',fullPage:true});}}
await page.setViewportSize({width:1440,height:1000});await page.goto(env.APP_URL+'/products');const searched=page.waitForResponse(r=>r.url().includes('search=does-not-exist'));await page.locator('#searchInput').fill('does-not-exist');await searched;await page.waitForTimeout(400);results.push({searchRows:await page.locator('table tbody tr').count()});
if(errors.length || results.some(r=>r.status && r.status!==200 || r.overflow))throw Error('Browser checks failed: '+JSON.stringify({results,errors}));
if(!await page.locator('table tbody').innerText().then(t=>t.includes('Aucun produit')))throw Error('AJAX empty state missing');
console.log(JSON.stringify({results,errors}));fs.writeFileSync('.audit/browser-results.json',JSON.stringify({results,errors},null,2));
}finally{await browser?.close();server.kill();}})().catch(e=>{console.error(e);process.exitCode=1;});
