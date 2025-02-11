{
  containerDefinitions: [
    {
      entryPoint: ['bash', '.github/ecspresso/development/oneshot_batch/entry_point.sh'],
      name: 'batch',
      image: '967125328503.dkr.ecr.ap-northeast-1.amazonaws.com/woa_laravel:' + std.extVar('TAG'),
      cpu: 0,
      interactive: true,
      pseudoTerminal: true,
      "environment": [
        {
          "name": "APP_NAME",
          "value": "woa"
        },
        {
          "name": "APP_ENV",
          "value": "development"
        },
        {
          "name": "APP_DEBUG",
          "value": "true"
        },
        {
          "name": "APP_LOG_LEVEL",
          "value": "debug"
        },
        {
          "name": "APP_URL",
          "value": "https://development.jinzaibank.com/woa"
        },
        {
          "name": "DB_CONNECTION",
          "value": "mysql"
        },
        {
          "name": "DB_HOST",
          "value": "dev-jinzaibank-db-cluster.cluster-c1jvfkkua8gb.ap-northeast-1.rds.amazonaws.com"
        },
        {
          "name": "DB_PORT",
          "value": "3306"
        },
        {
          "name": "DB_DATABASE",
          "value": "dev_woa"
        },
        {
          "name": "DB_USERNAME",
          "value": "dev_woa_u"
        },
        {
          "name": "DB_HOST_S",
          "value": "dev-jinzaibank-db-cluster.cluster-ro-c1jvfkkua8gb.ap-northeast-1.rds.amazonaws.com"
        },
        {
          "name": "DB_USERNAME_S",
          "value": "dev_woa_s"
        },
        {
          "name": "DB_USERNAME_A",
          "value": "dev_woa_a"
        },
        {
          "name": "DB_HOST_COMEDICAL",
          "value": "dev-jinzaibank-db-cluster.cluster-ro-c1jvfkkua8gb.ap-northeast-1.rds.amazonaws.com"
        },
        {
          "name": "DB_PORT_COMEDICAL",
          "value": "3306"
        },
        {
          "name": "DB_DATABASE_COMEDICAL",
          "value": "dev_comedical"
        },
        {
          "name": "DB_USERNAME_COMEDICAL",
          "value": "dev_co_s"
        },
        {
          "name": "BROADCAST_DRIVER",
          "value": "log"
        },
        {
          "name": "CACHE_DRIVER",
          "value": "file"
        },
        {
          "name": "SESSION_DRIVER",
          "value": "memcached"
        },
        {
          "name": "SESSION_SECURE_COOKIE",
          "value": "true"
        },
        {
          "name": "QUEUE_DRIVER",
          "value": "sync"
        },
        {
          "name": "MEMCACHED_HOST_1",
          "value": "dev-jinzaibank-cache.wce9tk.cfg.apne1.cache.amazonaws.com"
        },
        {
          "name": "MEMCACHED_HOST_2",
          "value": "dev-jinzaibank-cache.wce9tk.cfg.apne1.cache.amazonaws.com"
        },
        {
          "name": "MEMCACHED_PORT",
          "value": "11211"
        },
        {
          "name": "MEMCACHED_USERNAME",
          "value": ""
        },
        {
          "name": "MEMCACHED_PASSWORD",
          "value": ""
        },
        {
          "name": "REDIS_HOST",
          "value": "127.0.0.1"
        },
        {
          "name": "REDIS_PASSWORD",
          "value": "null"
        },
        {
          "name": "REDIS_PORT",
          "value": "6379"
        },
        {
          "name": "MAIL_DRIVER",
          "value": "smtp"
        },
        {
          "name": "MAIL_HOST",
          "value": "devstg-relay-conversion.bm-sms.info"
        },
        {
          "name": "MAIL_PORT",
          "value": "25"
        },
        {
          "name": "MAIL_ENCRYPTION",
          "value": "null"
        },
        {
          "name": "PUSHER_APP_ID",
          "value": ""
        },
        {
          "name": "PUSHER_APP_KEY",
          "value": ""
        },
        {
          "name": "PUSHER_APP_SECRET",
          "value": ""
        },
        {
          "name": "PUSHER_APP_CLUSTER",
          "value": "mt1"
        },
        {
          "name": "SF_LOGIN_URL",
          "value": "https://test.salesforce.com/"
        },
        {
          "name": "SF_USERNAME",
          "value": "salesforce_comedical@jinzaibank.mobi.partial"
        },
        {
          "name": "SLACK_URL",
          "value": "https://hooks.slack.com/services/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"
        },
        {
          "name": "S3_ACCESS_KEY",
          "value": ""
        },
        {
          "name": "S3_SECRET_KEY",
          "value": ""
        },
        {
          "name": "S3_REGION",
          "value": "ap-northeast-1"
        },
        {
          "name": "S3_BUCKET",
          "value": "dev-jb-pentaho"
        },
        {
          "name": "S3_STATIC_ACCESS_KEY",
          "value": ""
        },
        {
          "name": "S3_STATIC_SECRET_KEY",
          "value": ""
        },
        {
          "name": "S3_STATIC_REGION",
          "value": "ap-northeast-1"
        },
        {
          "name": "S3_STATIC_BUCKET",
          "value": "dev-gjb-static"
        },
        {
          "name": "STATIC_URL",
          "value": "https://dev-static.jinzaibank.com"
        },
        {
          "name": "S3_MAIL_REGION",
          "value": "ap-northeast-1"
        },
        {
          "name": "S3_MAIL_BUCKET",
          "value": "dev-jb-secure-co-mail"
        },
        {
          "name": "S3_BATCH_REGION",
          "value": "ap-northeast-1"
        },
        {
          "name": "S3_BATCH_BUCKET",
          "value": "dev-jb-batch"
        },
        {
          "name": "AWS_S3_GCP_DATA_SHARE_ACCESS_KEY",
          "value": ""
        },
        {
          "name": "AWS_S3_GCP_DATA_SHARE_SECRET_KEY",
          "value": ""
        },
        {
          "name": "AWS_S3_GCP_DATA_SHARE_REGION",
          "value": "ap-northeast-1"
        },
        {
          "name": "AWS_S3_GCP_DATA_SHARE_BUCKET",
          "value": "development-gcp-data-share"
        },
        {
          "name": "WILLMAIL_TARGETDB_ID_WOA",
          "value": "14551"
        },
        {
          "name": "JOBNOTE_URL",
          "value": "https://dev.job-note.jp"
        },
        {
          "name": "OPCACHE_URL",
          "value": "http://localhost"
        },
        {
          "name": "GOOGLE_CLOUD_APPLICATION_CREDENTIALS",
          "value": "/var/www/woa/dev-jinzaisystem-tool-woa-web.json"
        },
        {
          "name": "GOOGLE_CLOUD_PROJECT_ID",
          "value": "dev-jinzaisystem-tool"
        },
        {
          "name": "GOOGLE_DRIVE_INQUIRY_FOLDER_ID",
          "value": "12rhJQftHHWz_qv8VNwbFyrpGjzAB3bsv"
        },
        {
          "name": "GOOGLE_API_CLIENT_ID",
          "value": "1075386226576-3podrbijhke099nth9ato6c09j30inhp.apps.googleusercontent.com"
        },
        {
          "name": "GOOGLE_API_CLIENT_SECRET",
          "value": "01GicdW1IuclwFaZlHv7KZl9"
        },
        {
          "name": "GOOGLE_API_REFRESH_TOKEN",
          "value": "1//0eMP5a6MO7pKzCgYIARAAGA4SNwF-L9IrSKT1mugBU87a1Wt7M5tWVh49sU6hFG_uHfzbmb94DOn1gCeAqZA_wDdqJg1uH4sLouc"
        },
        {
          "name": "INDEED_FTP_HOST",
          "value": "ftp.indeed.com"
        },
        {
          "name": "INDEED_FTP_PORT",
          "value": "22"
        },
        {
          "name": "LOG_CHANNEL",
          "value": "stderr"
        },
        {
          "name": "SYSTEM_TYPE",
          "value": "webapp"
        },
        {
          name: 'COMMAND',
          value: std.extVar('COMMAND')
        }
      ],
      "secrets": [
        {
          "name": "APP_KEY",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:967125328503:secret:/woa/development/ecs/webapp/secrets-8lbDPg:APP_KEY::"
        },
        {
          "name": "DB_PASSWORD",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:967125328503:secret:/woa/development/ecs/webapp/secrets-8lbDPg:DB_PASSWORD::"
        },
        {
          "name": "DB_PASSWORD_S",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:967125328503:secret:/woa/development/ecs/webapp/secrets-8lbDPg:DB_PASSWORD_S::"
        },
        {
          "name": "DB_PASSWORD_A",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:967125328503:secret:/woa/development/ecs/webapp/secrets-8lbDPg:DB_PASSWORD_A::"
        },
        {
          "name": "DB_PASSWORD_COMEDICAL",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:967125328503:secret:/woa/development/ecs/webapp/secrets-8lbDPg:DB_PASSWORD_COMEDICAL::"
        },
        {
          "name": "MAIL_USERNAME",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:967125328503:secret:/woa/development/ecs/webapp/secrets-8lbDPg:MAIL_USERNAME::"
        },
        {
          "name": "MAIL_PASSWORD",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:967125328503:secret:/woa/development/ecs/webapp/secrets-8lbDPg:MAIL_PASSWORD::"
        }
        {
          "name": "ACC_MAILMGS_ENCRYPT_KEY64",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:967125328503:secret:/woa/development/ecs/webapp/secrets-8lbDPg:ACC_MAILMGS_ENCRYPT_KEY64::"
        },
        {
          "name": "SF_PASSWORD",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:967125328503:secret:/woa/development/ecs/webapp/secrets-8lbDPg:SF_PASSWORD::"
        },
        {
          "name": "WILLMAIL_ACCOUNT_KEY",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:967125328503:secret:/woa/development/ecs/webapp/secrets-8lbDPg:WILLMAIL_ACCOUNT_KEY::"
        },
        {
          "name": "WILLMAIL_API_KEY",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:967125328503:secret:/woa/development/ecs/webapp/secrets-8lbDPg:WILLMAIL_API_KEY::"
        },
        {
          "name": "INDEED_FTP_USERNAME",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:967125328503:secret:/woa/development/ecs/webapp/secrets-8lbDPg:INDEED_FTP_USERNAME::"
        },
        {
          "name": "INDEED_FTP_PASSWORD",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:967125328503:secret:/woa/development/ecs/webapp/secrets-8lbDPg:INDEED_FTP_PASSWORD::"
        }
      ],
      logConfiguration: {
        logDriver: 'awslogs',
        options: {
          'awslogs-create-group': 'true',
          'awslogs-group': '/ecs/woa/development/oneshot',
          'awslogs-region': 'ap-northeast-1',
          'awslogs-stream-prefix': 'batch',
        },
      },
    },
  ],
  cpu: '1024',
  executionRoleArn: 'arn:aws:iam::967125328503:role/ecsTaskExecutionRole',
  family: 'development-woa-oneshot-batch',
  ipcMode: '',
  memory: '2048',
  networkMode: 'awsvpc',
  pidMode: '',
  requiresCompatibilities: [
    'FARGATE',
  ],
  runtimePlatform: {
    cpuArchitecture: 'X86_64',
    operatingSystemFamily: 'LINUX',
  },
  taskRoleArn: 'arn:aws:iam::967125328503:role/ecs_task_woa_webapp',
}
