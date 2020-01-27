module.exports = {
  apps : [{
    name: 'Axis Frontend',
    script: './node_modules/nuxt/bin/nuxt.js',
    exec_mode : 'cluster',

    // Options reference: https://pm2.keymetrics.io/docs/usage/application-declaration/
    // args: 'one two',
    instances: 2,

    autorestart: true,
    watch: false,
    max_memory_restart: '1G',
    env: {
      NODE_ENV: 'development'
    },
    env_production: {
      NODE_ENV: 'production'
    }
  }],

//  deploy : {
//   production : {
//      user : 'node',
//      host : '212.83.163.1',
//      ref  : 'origin/master',
//      repo : 'git@github.com:repo.git',
//      path : '/var/www/production',
//      'post-deploy' : 'npm install && pm2 reload ecosystem.config.js --env production'
//    }
//  }


};
