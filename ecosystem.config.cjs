module.exports = {
    apps: [
        {
            name: "laravel-queue",
            script: "php",
            args: "artisan queue:work --queue=default --sleep=3 --tries=3 --timeout=300 --max-time=3600",
            interpreter: "none",
            cwd: "/var/www/html/netplus_management",
            exec_mode: "fork",
            instances: 1,
            autorestart: true,
            watch: false,
            max_memory_restart: "512M",
            env: {
                APP_ENV: "production"
            },
            log_date_format: "YYYY-MM-DD HH:mm:ss",
            error_file: "storage/logs/queue-error.log",
            out_file: "storage/logs/queue-out.log",
            merge_logs: true,
            kill_timeout: 5000,
            wait_ready: true
        },
        {
            name: "laravel-mikrotik-sync",
            script: "php",
            args: "artisan queue:work --queue=mikrotik-sync --sleep=1 --tries=3 --timeout=300 --max-time=3600",
            interpreter: "none",
            cwd: "/var/www/html/netplus_management",
            exec_mode: "fork",
            instances: 1,
            autorestart: true,
            watch: false,
            max_memory_restart: "512M",
            env: {
                APP_ENV: "production"
            },
            log_date_format: "YYYY-MM-DD HH:mm:ss",
            error_file: "storage/logs/mikrotik-sync-error.log",
            merge_logs: true,
            kill_timeout: 5000,
            wait_ready: true,
        },
    ]
};
