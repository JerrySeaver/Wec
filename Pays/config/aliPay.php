<?php
return [
    //应用ID,您的APPID。
    'app_id' => "2016092700609175",
    //
    'seller_id' => "2088102177523903",
    //商户私钥
    'merchant_private_key' => "MIIEpQIBAAKCAQEArK02Bdi/qx2cnup4+wEXdahbQXWPWPHBjDpRLXrLQDOc9VSYZR7aRK3jyzAagi7JDxke/rEXdYQrnnLpCwWAvWUMhFPYEEJTbI20liTYvbYL+3fiLFXsTuriRTtdUQ7zARTDb47V4lmLYwLokb94aFKbhZq8tJbbUMzaaoFeQhkQVXOW9zNtT1jhjXZblz/e+DPFppUhTcrwssvICPt2zMRoRRR2tF3YXLkF2GSsRUbu9UFu2CBj5w9C26pzBEBZW56PLtN2GsHxscO/YFagyLYXQDUSadEuKeshYqSP4Biyrn07eE6cbyq0x/Dqk9M/fQZWhvf7lGGTdkwHMgVkTQIDAQABAoIBAQCQC1X7fpo1FlimwJX3XSvCvJSTgIv5+IIqhiNduwd+IRAYH1+ZSyltDVnvD8utOXYDoEMY14XoRD9WyCjbRtXkKD1Ozdp5hbqt3W/9p/MeHpTUS2di8LJWCt6CPklT6xKPnlCB6TdGwMfj6nMz0fORoweVZkVwqD9/ocO6AP0uSPdrbhg3bTMkbfq5l5WV/TOsC2cbSUE0FoIa1/8Ml+P9e2g2u28MIRu5ZY8WjS7BcCcIVDMn2AbFMvwrOoe5CFYqM+/Qpl5bf9N/pkmGCQQKQuh9lElBA0pFlW7rjTWBcFJdVkmZHSo0oSdw4V9HlX1onni4MluC1LLK263mDbSJAoGBANOcGcLSQreE8haEGAM1v8lUjNfMUOYHFIoUm+W1GoHS1a6v1Dx1CdgylFnM3vyilGj4MITzjrYSHDyVMoCr7HL/vHsQWWaGTx6Hc2Ho7lN7zand/BSWZ5uUTCdH36i6dhAoSZckklxzdhQkkJgR+ZjC2Wf40+R1PomHfpTgX2lzAoGBANDmUN4mDra8/pu5pllw7j2V8hSCXyUjyAaBS2/v9MVpuCekpikJOWKcMAoLOKQhzt6xudjWpY8DqhwAcOisOL6JA5GaCp5fekZjiWwaVS19bM1RwTvWLpWC/q2t40UDIAJB+2FZcdFe7wnWl1ezUslcC63IJP2iFM4qaQwUU4s/AoGBAKLorFR2NvK+IPoAm9Psz0eaFgwUubs9fCyJjTOc51lHDUa9CrG4kOmmlttDg/AO4h6l5ASW1SmKa76gRg8VTKWECIDdp1Xs1IB2WvfnsYMH7t91jJn06LK3yqD7Dk8Brd7mhTEc8KjIIwGC3OaWAAgBCgqnX8mkEY8jhunqisnNAoGALTfNhWijgrbCPh8ffPjO+RG+BuZ5heaK462VELPWPe02aY5gUT2n6Ep6s5HePtj6mIXL/r3ntF9MsSb/Sb4NwmCSHzBC4Ck2pInjMz039tLRfFgYBcXE66QhUKL1MPkOMq7ppRbhZjqwb2a998PfRarg9EsAhs5QxpeziWPU+a0CgYEAx71Qp98ZqIyBfBHF8ULfZzcVXzgUhpkqhbnqHlg4PiKq9lsCRDF0aRWS01NBrNX+pGK6ZZeoemdxmrDiSpABClSOXZzL+XT6HDVow7n3/9u/SAgrkocZur2JDVlFgEZ5rn47sJQel6PrdrL9kumyqBBEUpzLAEyDkWedZnwQaL4=",
    
    //异步通知地址
    'notify_url' => "http://39.97.101.66/notify_url",
    
    //同步跳转
    'return_url' => "http:///39.97.101.66/return_url",
    //编码格式
    'charset' => "UTF-8",
    //签名方式
    'sign_type'=>"RSA2",
    //支付宝网关
    'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",
    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA1K9whmeljsL783V9t4EK6I0gk2nrXeXqlOdhXvMXWmruAK2rd+aZLV3xQuFvizihREYmm93cvzhlD4Nm/CRWhNlsvt5pGP99DMgmmZUi6bT1ddDrP9solVwmTmHciNwIOGL7JSicKfOcBxWQiss4HDliMbDDdV7jlmZySVIvjKSwb5/8g7Rj9FkTwhvldoMXeVh93V3/JNopN+Yl/X594OSyvYtegXIhWB6StSh3VGKm5iyK9WxICzLNfSvim0jV7jIiPbyMfSM7VCEQURo+XeGyFlNn59Z5A2+RNuRDMSx9hzUoZlCOGWtvh470nf2TtWSMWQKezjRlO/eCmRbQpwIDAQAB",
];