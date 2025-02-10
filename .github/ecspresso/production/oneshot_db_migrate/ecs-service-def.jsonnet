{
  deploymentConfiguration: {
    deploymentCircuitBreaker: {
      enable: true,
      rollback: true,
    },
    maximumPercent: 200,
    minimumHealthyPercent: 100,
  },
  deploymentController: {
    type: 'ECS',
  },
  enableECSManagedTags: true,
  enableExecuteCommand: false,
  launchType: 'FARGATE',
  networkConfiguration: {
    awsvpcConfiguration: {
      assignPublicIp: 'DISABLED',
      securityGroups: [
        "sg-0db82d839fa5582e1"
      ],
      subnets: [
        "subnet-0df8bd0b4a35dfe71",
        "subnet-0de7cdfc8d328f2d3"
      ],
    },
  },
  pendingCount: 0,
  platformFamily: 'Linux',
  platformVersion: 'LATEST',
  propagateTags: 'NONE',
  runningCount: 0,
  schedulingStrategy: 'REPLICA',
}