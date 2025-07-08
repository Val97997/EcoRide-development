<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Document\Review;
use App\Entity\Carshare;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Persistence\ManagerRegistry;
use Dom\Document;

/**
 * @extends ServiceEntityRepository<Carshare>
 */
class CarshareRepository extends ServiceEntityRepository
{
    private DocumentManager $dm;
    public function __construct(ManagerRegistry $registry, DocumentManager $documentManager)
    {
        parent::__construct($registry, Carshare::class);
        $this->dm = $documentManager;
    }

// Search Filter manage section
    public function findSearch(SearchData $search): array{

        // $reviews = $this->dm->getRepository(Review::class)->findBy(['status' => 'approved']);
        //create the search query
        $query = $this->createQueryBuilder('c')
            ->orderBy('c.departure_date', 'ASC')
            // ->select('c')
            ->join('c.user', 'u')
            ->join('c.car', 'ca');
            // we join the car and driver User linked to the carshare for ulterior specifications and details

        // create the PDO Query statement for fltering arrival locations and departure locations with the departure date:
        if(!empty($search->arrival_location)){
            $query->andWhere('c.arrival_location LIKE :arrival_location AND c.departure_location LIKE :departure_location
            AND c.departure_date >= :departure_date')
            ->setParameter('departure_date', $search->departure_date)
            ->setParameter('departure_location', "%{$search->departure_location}%")
            ->setParameter('arrival_location', "%{$search->arrival_location}%");
        }
        // create the PDO Query statement for filtering the max price:
        if(!empty($search->max)){
            $query->andWhere('c.price <= :max')
            ->setParameter('max', $search->max);
        }

        if(!empty($search->duration)){
            $durationHours = $search->duration->h + ($search->duration->d * 24);
            // Here we write the SQL builder Query in order to get the datetime interval to filter by duration 
            // (we had to install DQN functions library dependency) :
            $query->andWhere('
            (TIMESTAMPDIFF(DAY, c.departure_date, c.arrival_date) * 24) + ABS(TIMESTAMPDIFF(HOUR, c.departure_hour, c.arrival_hour))
            < :duration')
            ->setParameter('duration', $durationHours);
        }
        
        if(!empty($search->eco) && $search->eco){
            $query->andWhere('ca.fuel LIKE :ecological')
            ->setParameter('ecological', 'electric');
        }
        $result = $query->getQuery()->getResult();
        // If there is no reviews, we return the result
        if(!empty($search->rating)){
            $invalidArray = [];
            foreach ($result as $line) {
            $id = ($line->getUser())->getId();
            $reviews = $this->dm->getRepository(Review::class)->findBy(['userId' => $id, 'status' => 'approved']);
            foreach ($reviews as $review){
                    if($review->getRating() < $search->rating){
                        $invalidArray[] = $line;
                        break;
                    }
                }
            }
            $result = array_filter($result, function($item) use ($invalidArray) {
                return !in_array($item, $invalidArray, true);
            });
            $result = array_values($result); // reindex array
        }
        return $result;
        
    }

    public function getDailyRoutes(){
        return $this->createQueryBuilder('r')
            ->select('SUBSTRING(r.departure_date, 1, 10) as day, COUNT(r.id) as count')
            ->groupBy('day')
            ->getQuery()
            ->getResult();
    }

    public function getEarnedCredits(){
        return $this->createQueryBuilder('c')
            ->select('c.departure_date as day, COUNT(p.id) as passenger_count')
            ->leftJoin('c.passengers', 'p')
            ->groupBy('day')
            ->getQuery()
            ->getResult();
    }

    public function getTotalBenef(){
        return $this->createQueryBuilder('m')
        // get the count of passengers for each Carshare entity, and retrieve two credits for each booked seat
            ->select('COUNT(p.id) * 2 as earnings')
            ->leftJoin('m.passengers', 'p')
            ->getQuery()
            ->getResult();
    }
}
