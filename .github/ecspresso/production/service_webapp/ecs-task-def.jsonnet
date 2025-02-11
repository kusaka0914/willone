{
  "containerDefinitions": [
    {
      "name": "laravel",
      "cpu": 0,
      "essential": true,
      "image": "622803604774.dkr.ecr.ap-northeast-1.amazonaws.com/woa_laravel:" + std.extVar('Tag'),
      "environment": [
        {
          "name": "APP_NAME",
          "value": "woa"
        },
        {
          "name": "APP_ENV",
          "value": "production"
        },
        {
          "name": "APP_DEBUG",
          "value": "false"
        },
        {
          "name": "APP_LOG_LEVEL",
          "value": "debug"
        },
        {
          "name": "APP_URL",
          "value": "https://www.jinzaibank.com/woa"
        },
        {
          "name": "DB_CONNECTION",
          "value": "mysql"
        },
        {
          "name": "DB_HOST",
          "value": "prd-jinzaibank-db.cluster-cyowmhxcooyu.ap-northeast-1.rds.amazonaws.com"
        },
        {
          "name": "DB_PORT",
          "value": "3306"
        },
        {
          "name": "DB_DATABASE",
          "value": "db_woa"
        },
        {
          "name": "DB_USERNAME",
          "value": "db_woa_u"
        },
        {
          "name": "DB_HOST_S",
          "value": "prd-jinzaibank-db.cluster-ro-cyowmhxcooyu.ap-northeast-1.rds.amazonaws.com"
        },
        {
          "name": "DB_USERNAME_S",
          "value": "db_woa_s"
        },
        {
          "name": "DB_HOST_COMEDICAL",
          "value": "prd-jinzaibank-db.cluster-ro-cyowmhxcooyu.ap-northeast-1.rds.amazonaws.com"
        },
        {
          "name": "DB_PORT_COMEDICAL",
          "value": "3306"
        },
        {
          "name": "DB_DATABASE_COMEDICAL",
          "value": "db_comedical"
        },
        {
          "name": "DB_USERNAME_COMEDICAL",
          "value": "db_co_s"
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
          "value": "prd-ec-node1.xjb"
        },
        {
          "name": "MEMCACHED_HOST_2",
          "value": "prd-ec-node2.xjb"
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
          "value": "woa.prd-relay-conversion.bm-sms.info"
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
          "value": "https://login.salesforce.com"
        },
        {
          "name": "SF_USERNAME",
          "value": "salesforce_comedical@jinzaibank.mobi"
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
          "value": "prd-jinzaibank-pentaho"
        },
       {
          "name": "S3_BATCH_REGION",
          "value": "ap-northeast-1"
        },
        {
          "name": "S3_BATCH_BUCKET",
          "value": "prd-jinzaibank-batch"
        },
       {
          "name": "S3_CO_IMAGE_REGION",
          "value": "ap-northeast-1"
        },
        {
          "name": "S3_CO_IMAGE_BUCKET",
          "value": "prd-co-image"
        },
        {
          "name": "JOBNOTE_URL",
          "value": "https://job-note.jp"
        },
        {
          "name": "S3_CO_IMAGE_URL",
          "value": "https://image.jinzaibank.com"
        },
        {
          "name": "OPCACHE_URL",
          "value": "http://localhost"
        },
        {
          "name": "KUROHON_MYPAGE",
          "value": "https://kurohon.jp/my-page/"
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
          "name": "HUBSPOT_REDIRECT_URI",
          "value": "https://www.jinzaibank.com/woa/admin/hubspot/callback"
        },
        {
          "name": "HUBSPOT_API_URL",
          "value": "https://api.hubapi.com"
        },
        {
          "name": "HUBSPOT_OAUTH_URL",
          "value": "https://app.hubspot.com/oauth/authorize"
        },
        {
          "name": "HUBSPOT_GET_TOKEN_ENDPOINT",
          "value": "/oauth/v1/token"
        },
        {
          "name": "HUBSPOT_GET_CONTACT_ENDPOINT",
          "value": "/crm/v3/objects/contacts"
        },
        {
          "name": "SENTRY_TRACES_SAMPLE_RATE",
          "value": "0.0"
        },
        {
          "name": "SENTRY_SAMPLE_RATE",
          "value": "1.0"
        },
        {
          "name": "DD_AGENT_HOST",
          "value": "localhost"
        },
        {
          "name": "DD_ENV",
          "value": "production"
        },
        {
          "name": "DD_SERVICE",
          "value": "woa"
        },
        {
          "name": "DD_VERSION",
          "value": std.extVar('Tag')
        },
      ],
      "secrets": [
        {
          "name": "APP_KEY",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:622803604774:secret:/woa/production/ecs/webapp/secrets-coupzU:APP_KEY::"
        },
        {
          "name": "DB_PASSWORD",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:622803604774:secret:/woa/production/ecs/webapp/secrets-coupzU:DB_PASSWORD::"
        },
        {
          "name": "DB_PASSWORD_S",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:622803604774:secret:/woa/production/ecs/webapp/secrets-coupzU:DB_PASSWORD_S::"
        },
        {
          "name": "DB_PASSWORD_COMEDICAL",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:622803604774:secret:/woa/production/ecs/webapp/secrets-coupzU:DB_PASSWORD_COMEDICAL::"
        },
        {
          "name": "MAIL_USERNAME",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:622803604774:secret:/woa/production/ecs/webapp/secrets-coupzU:MAIL_USERNAME::"
        },
        {
          "name": "MAIL_PASSWORD",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:622803604774:secret:/woa/production/ecs/webapp/secrets-coupzU:MAIL_PASSWORD::"
        }
        {
          "name": "ACC_MAILMGS_ENCRYPT_KEY64",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:622803604774:secret:/woa/production/ecs/webapp/secrets-coupzU:ACC_MAILMGS_ENCRYPT_KEY64::"
        },
        {
          "name": "SF_PASSWORD",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:622803604774:secret:/woa/production/ecs/webapp/secrets-coupzU:SF_PASSWORD::"
        },
        {
          "name": "HUBSPOT_CLIENT_ID",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:622803604774:secret:/woa/production/ecs/webapp/secrets-coupzU:HUBSPOT_CLIENT_ID::"
        },
        {
          "name": "HUBSPOT_CLIENT_SECRET",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:622803604774:secret:/woa/production/ecs/webapp/secrets-coupzU:HUBSPOT_CLIENT_SECRET::"
        },
        {
          "name": "SENTRY_LARAVEL_DSN",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:622803604774:secret:/woa/production/ecs/webapp/secrets-coupzU:SENTRY_LARAVEL_DSN::"
         }
      ],
      healthCheck: {
        command: [
          'CMD-SHELL',
          'curl -f http://localhost || exit 1',
        ],
        interval: 30,
        retries: 3,
        timeout: 5,
      },
      "logConfiguration": {
        "logDriver": "awslogs",
        "options": {
          "awslogs-create-group": "true",
          "awslogs-group": "/ecs/woa/production/webapp",
          "awslogs-region": "ap-northeast-1",
          "awslogs-stream-prefix": "ecs"
        }
      },
      "dockerLabels": {
        "com.datadoghq.tags.env": "production",
        "com.datadoghq.tags.service": "woa",
        "com.datadoghq.tags.version": std.extVar('Tag')
      },
      "portMappings": [
        {
          "appProtocol": "http",
          "containerPort": 80,
          "hostPort": 80,
          "name": "laravel-80-tcp",
          "protocol": "tcp"
        }
      ]
    },
    {
      "name": "datadog-agent-woa-prd",
      "image": "public.ecr.aws/datadog/agent:latest",
      "cpu": 10,
      "memory": 512,
      "essential": false,
      "portMappings": [
        {
          "hostPort": 8126,
          "protocol": "tcp",
          "containerPort": 8126
        }
      ],
      "environment": [
        {
          "name": "ECS_FARGATE",
          "value": "true"
        },
        {
          "name": "DD_SITE",
          "value": "ap1.datadoghq.com"
        },
        {
          "name": "DD_CHECKS_TAG_CARDINALITY",
          "value": "orchestrator"
        },
        {
          "name": "DD_DOGSTATSD_TAG_CARDINALITY",
          "value": "orchestrator"
        },
        {
          "name": "DD_APM_NON_LOCAL_TRAFFIC",
          "value": "true"
        },
        {
          "name": "DD_APM_ENABLED",
          "value": "true"
        },
        {
          "name": "DD_PROFILING_ENABLED",
          "value": "true"
        },
        {
          "name": "DD_PROCESS_AGENT_ENABLED",
          "value": "true"
        },
        {
          "name": "DD_DOGSTATSD_NON_LOCAL_TRAFFIC",
          "value": "true"
        },
        {
          "name": "DD_TAGS",
          "value": "environment:production"
        },
        {
          "name": "DD_ENV",
          "value": "production"
        },
        {
          "name": "DD_SERVICE",
          "value": "woa"
        },
        {
          "name": "DD_VERSION",
          "value": std.extVar('Tag')
        },
        {
          "name": "DD_APM_MAX_TPS",
          "value": "1"
        },
        {
          "name": "DD_APM_ENABLE_RARE_SAMPLER",
          "value": "true"
        },
      ],
      "dockerLabels": {
        "com.datadoghq.tags.env": "production",
        "com.datadoghq.tags.service": "woa",
        "com.datadoghq.tags.version": std.extVar('Tag')
      },
      "secrets": [
        {
          "name": "DD_API_KEY",
          "valueFrom": "arn:aws:secretsmanager:ap-northeast-1:622803604774:secret:/woa/production/ecs/webapp/secrets-coupzU:DD_API_KEY::"
        }
      ],
      "logConfiguration": {
        "logDriver": "awslogs",
        "options": {
          "awslogs-create-group": "true",
          "awslogs-group": "/ecs/woa/production/webapp",
          "awslogs-region": "ap-northeast-1",
          "awslogs-stream-prefix": "datadog"
        }
      },
      healthCheck: {
        retries: 3,
        command: ["CMD-SHELL","agent health"],
        timeout: 5,
        interval: 30,
        startPeriod: 15
      },
    }
  ],
  "cpu": "2048",
  "executionRoleArn": "arn:aws:iam::622803604774:role/ecsTaskExecutionRole",
  "family": "production-woa-webapp",
  "ipcMode": "",
  "memory": "4096",
  "networkMode": "awsvpc",
  "pidMode": "",
  "requiresCompatibilities": [
    "FARGATE"
  ],
  "runtimePlatform": {
    "cpuArchitecture": "X86_64",
    "operatingSystemFamily": "LINUX"
  },
  "taskRoleArn": "arn:aws:iam::622803604774:role/ecs_task_woa_webapp"
}
