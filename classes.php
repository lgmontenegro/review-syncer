<?php

final Class Review
{
    private $pdo;
    
    public function setPdo($pdo)
    {
        $this->pdo = $pdo;
    }

    private function formatJsonDate($date)
    {
        $date = explode('T', $review->created_at);
        $date = implode(' ', $date);
        $date = explode('.', $date);
        return $date[0];
    }

    public function insert($arrayReviews, $app_slug)
    {
        foreach($arrayReviews as $review){
            if(is_null($review->shop_domain)){
                continue;
            }
            if(!$this->getByShopifyDomain($review->shop_domain)){
                $date = $this->formatJsonDate($review->created_at);
                $insertStatement = $this->pdo->insert(array('shopify_domain', 'app_slug', 'star_rating', 'created_at'))
                    ->into('shopify_app_reviews')
                    ->values([$review->shop_domain, $app_slug, $star_rating, $date[0]]);
                $insertReview = $insertStatement->execute(false);
            }else{
                $this->update($review);
            }
        }
        return $insertReview;
    }

    public function update($review)
    {
        
    }

    public function getByShopifyDomain($shop_domain)
    {
        $query = $this->pdo->select()->from('shopify_app_reviews')->where('shopify_domain', '=', $shop_domain);
        $stmt = $query->execute();
        return $stmt->fetch();
    }
}