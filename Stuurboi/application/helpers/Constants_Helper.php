<?php
  
Interface Constants{
    const HTTP_POST = 'POST';
    const HTTP_GET = 'GET';
    const HTTP_PUT = 'PUT';
    const HTTP_DELETE = 'DELETE';
    
    const API_AUTH_USERNAME = 'admin';
    const API_AUTH_PASSWORD = '1234';
    const API_KEY = 'CODEX@123';
    
    const USER_DRIVER = 'driver';
    const USER_OWNER = 'owner';
    const USER_CLIENT = 'client';
    const USER_ADMIN = 'admin';
    
    const TRANSPORT_CAR = 'car';
    const TRANSPORT_MOTORBIKE = 'bike';
    const TRANSPORT_BAKKIE = 'bakkie';
    const TRANSPORT_TRUCK = 'truck';
    
    const STATUS_ACTIVE = 'active';
    const STATUS_UNACTIVE = 'unactive';
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REJECTED = 'rejected';
    const STATUS_ACCEPTED = 'accepted';
    
    const LEFT = 'left';
    const RIGHT = 'right';
    const UP = 'up';
    const DOWN = 'down';
    const CENTER = 'center';
    const INNER = 'inner';
    const OUTER = 'outer';
    const NORTH = 'north';
    const SOUTH = 'south';
    const EAST = 'east';
    const WEST = 'west';
    
    const BOOLEAN_TRUE = 'true';
    const BOOLEAN_FALSE = 'false';
}