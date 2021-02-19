<?php
    /**
     * Generate random UUID
     * 
     * // Example
     * $value = uuid();
     * the generated value is random, but it will follow this pattern:
     * $value = '8f98a73a-10b7-4cec-8d32-36748cbcad33';
     */
    function uuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }    
?>