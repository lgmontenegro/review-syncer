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
        $date1 = explode('T', $review->created_at);
        $date2 = implode(' ', $date1);
        $date3 = explode('.', $date2);
        
        return $date3[0];
    }

    public function insert($arrayReviews, $app_slug)
    {
        foreach($arrayReviews as $review){
            if(is_null($review->shop_domain)){
                continue;
            }
            if(!$this->getByShopifyDomain($review->shop_domain)){
                
                
                $date1 = explode('T', $review->created_at);
                $date2 = implode(' ', $date1);
                $date3 = explode('.', $date2);
                
                return $date3[0];

                $insertStatement = $this->pdo->insert(array('shopify_domain', 'app_slug', 'star_rating', 'created_at'))
                    ->into('shopify_app_reviews')
                    ->values([$review->shop_domain, $app_slug, $review->star_rating, $date]);
                $insertReview = $insertStatement->execute(false);
            }else{
                $this->update($review);
            }
        }
        return $insertReview;
    }

    public function update($review)
    {
        if($this->getByShopifyDomain($review->shop_domain)){
            $reviewUpdate = $this->getByShopifyDomain($review->shop_domain);
            
            $date = $this->formatJsonDate($review->updated_at);
            $updateStatement = $this->pdo->update(['previous_start_rating'=>$reviewUpdate->start_rating, 'updated_at'=>$date])
                ->into('shopify_app_reviews')
                ->where('shopify_domain', '=', $review->shop_domain);
            $insertReview = $insertStatement->execute(false);
        }else{
            $this->update($review);
        }
    }

    public function getByShopifyDomain($shop_domain)
    {
        $query = $this->pdo->select()->from('shopify_app_reviews')->where('shopify_domain', '=', $shop_domain);
        $stmt = $query->execute();
        return $stmt->fetch();
    }
}